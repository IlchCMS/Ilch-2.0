<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Models\Address as AddressModel;
use Modules\Shop\Models\Customer as CustomerModel;
use Modules\Shop\Models\Order as OrderModel;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

class CustomerTest extends DatabaseTestCase
{
    /**
     * @var Customer
     */
    protected Customer $out;

    /**
     * Paths of the invoice PDF files created by the tests.
     *
     * @var string[]
     */
    protected array $createdInvoiceFiles = [];

    /**
     * Configured retention period in years, passed to the mapper calls.
     *
     * Documents arising before 2025 remain subject to the previous
     * 10-year period (4th Bureaucracy Reduction Act), so every sample
     * order is effectively retained for 10 years.
     */
    private const RETENTION_YEARS = 8;

    public function setUp(): void
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', sys_get_temp_dir() . '/ilch-shop-test');
        }

        parent::setUp();
        $this->out = new Customer();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdInvoiceFiles as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $this->createdInvoiceFiles = [];

        parent::tearDown();
    }

    /**
     * Tests that getCustomers() returns all customers from the sample data.
     */
    public function testGetCustomers()
    {
        $customers = $this->out->getCustomers();

        self::assertIsArray($customers);
        self::assertCount(4, $customers);
        self::assertInstanceOf(CustomerModel::class, $customers[0]);
    }

    /**
     * Tests that getCustomerById() returns the correct fields for the first sample customer.
     */
    public function testGetCustomerByIdFields()
    {
        $customer = $this->out->getCustomerById(1);

        self::assertInstanceOf(CustomerModel::class, $customer);
        self::assertEquals(1, $customer->getId());
        self::assertEquals(1, $customer->getUserId());
        self::assertEquals('max@mustermann.de', $customer->getEmail());
    }

    /**
     * Tests that getCustomerById() returns the correct fields for the second sample customer.
     */
    public function testGetCustomerByIdSecond()
    {
        $customer = $this->out->getCustomerById(2);

        self::assertInstanceOf(CustomerModel::class, $customer);
        self::assertEquals(2, $customer->getId());
        self::assertEquals(2, $customer->getUserId());
        self::assertEquals('eva@musterfrau.de', $customer->getEmail());
    }

    /**
     * Tests that getCustomers() accepts a where array.
     */
    public function testGetCustomersWhere()
    {
        $customers = $this->out->getCustomers(['userId' => 3]);

        self::assertIsArray($customers);
        self::assertCount(1, $customers);
        self::assertEquals(3, $customers[0]->getId());
        self::assertEquals('bernd@mustermann.de', $customers[0]->getEmail());
    }

    /**
     * Tests that getCustomers() returns an empty array when no customers exist.
     */
    public function testGetCustomersEmpty()
    {
        foreach ([1, 2, 3, 4] as $id) {
            $this->out->delete($id);
        }

        $customers = $this->out->getCustomers();

        self::assertIsArray($customers);
        self::assertCount(0, $customers);
    }

    /**
     * Tests that getCustomerById() returns false for a non-existent id.
     */
    public function testGetCustomerByIdNotFound()
    {
        $customer = $this->out->getCustomerById(9999);

        self::assertFalse($customer);
    }

    /**
     * Tests that getCustomerByUserId() returns the correct customer.
     */
    public function testGetCustomerByUserId()
    {
        $customer = $this->out->getCustomerByUserId(2);

        self::assertInstanceOf(CustomerModel::class, $customer);
        self::assertEquals(2, $customer->getId());
        self::assertEquals(2, $customer->getUserId());
        self::assertEquals('eva@musterfrau.de', $customer->getEmail());
    }

    /**
     * Tests that getCustomerByUserId() returns false for a non-existent user id.
     */
    public function testGetCustomerByUserIdNotFound()
    {
        $customer = $this->out->getCustomerByUserId(9999);

        self::assertFalse($customer);
    }

    /**
     * Tests inserting a new customer via save().
     */
    public function testSaveInsert()
    {
        $model = new CustomerModel();
        $model->setId(0)
            ->setUserId(99)
            ->setEmail('new@customer.test');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(4, $newId);

        $customer = $this->out->getCustomerById($newId);

        self::assertInstanceOf(CustomerModel::class, $customer);
        self::assertEquals(99, $customer->getUserId());
        self::assertEquals('new@customer.test', $customer->getEmail());

        self::assertCount(5, $this->out->getCustomers());
    }

    /**
     * Tests that save() with id 0 performs an insert and does not affect existing customers.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getCustomers();
        self::assertCount(4, $before);

        $model = new CustomerModel();
        $model->setId(0)
            ->setUserId(100)
            ->setEmail('insert@example.com');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(4, $newId);

        $after = $this->out->getCustomers();
        self::assertCount(5, $after);

        $existing = $this->out->getCustomerById(1);
        self::assertEquals('max@mustermann.de', $existing->getEmail());
    }

    /**
     * Tests updating an existing customer via save().
     */
    public function testSaveUpdate()
    {
        $model = new CustomerModel();
        $model->setId(1)
            ->setUserId(10)
            ->setEmail('updated@example.com');

        $affected = $this->out->save($model);

        self::assertSame(1, $affected);

        $customer = $this->out->getCustomerById(1);

        self::assertInstanceOf(CustomerModel::class, $customer);
        self::assertEquals(1, $customer->getId());
        self::assertEquals(10, $customer->getUserId());
        self::assertEquals('updated@example.com', $customer->getEmail());
    }

    /**
     * Tests that update does not affect other customers.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CustomerModel();
        $model->setId(1)
            ->setUserId(11)
            ->setEmail('changed@example.com');

        $this->out->save($model);

        $other = $this->out->getCustomerById(2);

        self::assertInstanceOf(CustomerModel::class, $other);
        self::assertEquals(2, $other->getUserId());
        self::assertEquals('eva@musterfrau.de', $other->getEmail());
    }

    /**
     * Tests that delete() removes a customer.
     */
    public function testDelete()
    {
        $deleted = $this->out->delete(1);

        self::assertTrue($deleted);
        self::assertFalse($this->out->getCustomerById(1));

        $customers = $this->out->getCustomers();
        self::assertCount(3, $customers);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $deleted = $this->out->delete(9999);

        self::assertFalse($deleted);

        $customers = $this->out->getCustomers();
        self::assertCount(4, $customers);
    }

    /**
     * Tests that deleteComplete() returns false when the customer does not exist.
     */
    public function testDeleteCompleteNotFound()
    {
        self::assertFalse($this->out->deleteComplete(9999, self::RETENTION_YEARS));

        self::assertCount(4, $this->out->getCustomers());
    }

    /**
     * Tests that deleteComplete() deletes a customer that has no orders.
     */
    public function testDeleteCompleteWithoutOrders()
    {
        $model = new CustomerModel();
        $model->setId(0)
            ->setUserId(99)
            ->setEmail('noorders@customer.test');

        $id = $this->out->save($model);

        self::assertTrue($this->out->deleteComplete($id, self::RETENTION_YEARS));
        self::assertFalse($this->out->getCustomerById($id));
        self::assertCount(4, $this->out->getCustomers());
    }

    /**
     * Tests that deleteComplete() deletes the customer when none of its orders
     * has an invoice within the retention period.
     */
    public function testDeleteComplete()
    {
        // Sample order 1 has no invoice file, so nothing has to be retained.
        self::assertTrue($this->out->deleteComplete(1, self::RETENTION_YEARS));

        self::assertFalse($this->out->getCustomerById(1));
        self::assertCount(3, $this->out->getCustomers());

        // The order and its details cascade via the foreign keys.
        self::assertCount(0, $this->db->select('*')->from('shop_orders')
            ->where(['customerId' => 1])->execute()->fetchRows());
        self::assertCount(0, $this->db->select('*')->from('shop_orderdetails')
            ->where(['orderId' => 1])->execute()->fetchRows());

        // The address rows cascade via the foreign key.
        self::assertCount(0, $this->db->select('*')->from('shop_addresses')
            ->where(['customerId' => 1])->execute()->fetchRows());

        // The remaining sample orders are untouched.
        self::assertCount(3, (new OrdersMapper())->getOrders());
    }

    /**
     * Tests that deleteComplete() removes the invoice PDF files of all orders
     * of a customer once all retention periods have expired.
     */
    public function testDeleteCompleteRemovesExpiredInvoiceFiles()
    {
        $ordersMapper = new OrdersMapper();

        // Push sample order 1 back into 2015: retention ended 31-12-2025.
        $order1 = $ordersMapper->getOrderById(1);
        $order1->setDatetime('2015-03-10 10:00:00');
        $order1->setInvoiceFilename('order-1-invoice');
        $ordersMapper->save($order1);

        // A second, older order for the same customer (retention ended 31-12-2024).
        $address = $this->createAddressModel(
            0,
            1,
            'Max',
            'Mustermann',
            'Musterstr. 1',
            '12345',
            'Musterstadt',
            'Deutschland'
        );
        $order2 = new OrderModel();
        $order2
            ->setId(0)
            ->setDatetime('2014-06-20 14:00:00')
            ->setCurrencyId(1)
            ->setCustomerId(1)
            ->setInvoiceAddress($address)
            ->setDeliveryAddress($address)
            ->setEmail('max@mustermann.de')
            ->setOrderdetails([$this->createOrderdetailsModel(1, 25.00, 1, 19, 0.00)])
            ->setInvoiceFilename('order-2-invoice')
            ->setDatetimeInvoiceSent('')
            ->setWillCollect(0)
            ->setSelector('')
            ->setConfirmCode('')
            ->setStatus(0);
        $ordersMapper->save($order2);

        $path1 = $this->createInvoiceFile('order-1-invoice');
        $path2 = $this->createInvoiceFile('order-2-invoice');

        self::assertTrue($this->out->deleteComplete(1, self::RETENTION_YEARS));

        self::assertFileDoesNotExist($path1);
        self::assertFileDoesNotExist($path2);
        self::assertFalse($this->out->getCustomerById(1));
        self::assertCount(3, $ordersMapper->getOrders());
    }

    /**
     * Tests that deleteComplete() refuses to delete the customer while one of
     * its orders still has an invoice within the retention period.
     */
    public function testDeleteCompleteBlockedByRetentionPeriod()
    {
        $ordersMapper = new OrdersMapper();
        $order = $ordersMapper->getOrderById(1);

        // Sample order 1 was placed in 2020: retention runs until 31-12-2030,
        // so the invoice still has to be kept.
        $order->setInvoiceFilename('order-1-invoice');
        $ordersMapper->save($order);

        $path = $this->createInvoiceFile('order-1-invoice');
        self::assertFileExists($path);

        self::assertFalse($this->out->deleteComplete(1, self::RETENTION_YEARS));

        // Nothing may have been touched.
        self::assertInstanceOf(CustomerModel::class, $this->out->getCustomerById(1));
        self::assertCount(4, $this->out->getCustomers());
        self::assertCount(1, $ordersMapper->getOrdersByCustomerId(1));
        self::assertFileExists($path);
    }

    /**
     * Creates a fake invoice PDF on disk and registers it for cleanup in tearDown().
     *
     * @param string $filename filename without extension
     * @return string absolute path of the created file
     */
    private function createInvoiceFile(string $filename): string
    {
        $path = ROOT_PATH . '/application/modules/shop/static/invoice/' . $filename . '.pdf';

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        file_put_contents($path, "%PDF-1.4\nfake invoice");
        $this->createdInvoiceFiles[] = $path;

        return $path;
    }

    private function createAddressModel(
        int $id,
        int $customerId,
        string $prename,
        string $lastname,
        string $street,
        string $postcode,
        string $city,
        string $country
    ): AddressModel {
        $address = new AddressModel();
        $address
            ->setId($id)
            ->setCustomerID($customerId)
            ->setPrename($prename)
            ->setLastname($lastname)
            ->setStreet($street)
            ->setPostcode($postcode)
            ->setCity($city)
            ->setCountry($country);

        return $address;
    }

    private function createOrderdetailsModel(
        int $itemId,
        float $price,
        int $quantity,
        int $tax,
        float $shippingCosts
    ): OrderdetailsModel {
        $detail = new OrderdetailsModel();
        $detail
            ->setId(0)
            ->setOrderId(0)
            ->setItemId($itemId)
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setTax($tax)
            ->setShippingCosts($shippingCosts);

        return $detail;
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $coreSql = 'CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                    `moduleKey` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    `desc` VARCHAR(255) NOT NULL,
                    `text` TEXT NOT NULL,
                    `locale` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                    (1, "Admin"),
                    (2, "Member"),
                    (3, "Guest");';

        $config = new ModuleConfig();

        return $coreSql . "\n" . $config->getInstallSql();
    }
}
