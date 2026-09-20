<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Models\Order as OrderModel;
use Modules\Shop\Models\Address as AddressModel;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

class OrdersMapperTest extends DatabaseTestCase
{
    protected OrdersMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new OrdersMapper();
    }

    /**
     * Tests that getOrders() returns all orders sorted by status ASC and id DESC.
     */
    public function testGetOrders()
    {
        $orders = $this->out->getOrders();

        self::assertIsArray($orders);
        self::assertCount(4, $orders);
        self::assertInstanceOf(OrderModel::class, $orders[0]);

        self::assertSame([4, 3, 2, 1], $this->getOrderIds($orders));
    }

    /**
     * Tests that getOrders() returns the expected fields for the first order.
     */
    public function testGetOrdersFields()
    {
        $orders = $this->out->getOrders();
        $order = $orders[0];

        self::assertEquals(4, $order->getId());
        self::assertEquals('2020-04-26 09:54:38', $order->getDatetime());
        self::assertEquals(1, $order->getCurrencyId());
        self::assertEquals(4, $order->getCustomerId());
        self::assertEquals('ingrid@musterfrau.de', $order->getEmail());
        self::assertEquals('', $order->getInvoiceFilename());
        self::assertEquals(0, $order->getWillCollect());
        self::assertEquals(0, $order->getStatus());

        self::assertTrue(in_array($order->getDatetimeInvoiceSent(), ['', null, '0000-00-00 00:00:00'], true));
        self::assertTrue(in_array($order->getSelector(), ['', null], true));
        self::assertTrue(in_array($order->getConfirmCode(), ['', null], true));

        $invoiceAddress = $order->getInvoiceAddress();
        self::assertInstanceOf(AddressModel::class, $invoiceAddress);
        self::assertEquals(4, $invoiceAddress->getId());
        self::assertEquals('Ingrid', $invoiceAddress->getPrename());
        self::assertEquals('Musterfrau', $invoiceAddress->getLastname());
        self::assertEquals('Musterstr. 7', $invoiceAddress->getStreet());
        self::assertEquals('34567', $invoiceAddress->getPostcode());
        self::assertEquals('Musterort', $invoiceAddress->getCity());
        self::assertEquals('Deutschland', $invoiceAddress->getCountry());

        $deliveryAddress = $order->getDeliveryAddress();
        self::assertInstanceOf(AddressModel::class, $deliveryAddress);
        self::assertEquals(4, $deliveryAddress->getId());
        self::assertEquals('Ingrid', $deliveryAddress->getPrename());
        self::assertEquals('Musterstr. 7', $deliveryAddress->getStreet());
        self::assertEquals('Musterort', $deliveryAddress->getCity());

        $details = $order->getOrderdetails();
        self::assertIsArray($details);
        self::assertCount(1, $details);
        self::assertInstanceOf(OrderdetailsModel::class, $details[0]);
        self::assertSame([8], $this->getOrderdetailIds($details));
        self::assertEquals(4, $details[0]->getOrderId());
        self::assertEquals(7, $details[0]->getItemId());
        self::assertEquals(65.90, $details[0]->getPrice());
        self::assertEquals(1, $details[0]->getQuantity());
        self::assertEquals(19, $details[0]->getTax());
        self::assertEquals(0.00, $details[0]->getShippingCosts());
    }

    /**
     * Tests that getOrders() returns the expected fields for the second order.
     */
    public function testGetOrdersSecond()
    {
        $orders = $this->out->getOrders();
        $order = $orders[1];

        self::assertEquals(3, $order->getId());
        self::assertEquals('2020-04-25 11:51:36', $order->getDatetime());
        self::assertEquals(1, $order->getCurrencyId());
        self::assertEquals(3, $order->getCustomerId());
        self::assertEquals('bernd@mustermann.de', $order->getEmail());
        self::assertEquals(1, $order->getStatus());

        $details = $order->getOrderdetails();
        self::assertIsArray($details);
        self::assertCount(3, $details);
        self::assertSame([5, 6, 7], $this->getOrderdetailIds($details));
    }

    /**
     * Tests that getOrders() returns an empty array when no orders exist.
     */
    public function testGetOrdersEmpty()
    {
        foreach ([1, 2, 3, 4] as $id) {
            $this->out->delete($id);
        }

        $orders = $this->out->getOrders();

        self::assertIsArray($orders);
        self::assertCount(0, $orders);
    }

    /**
     * Tests that getOrderById() returns the correct order.
     */
    public function testGetOrderById()
    {
        $order = $this->out->getOrderById(1);

        self::assertInstanceOf(OrderModel::class, $order);
        self::assertEquals(1, $order->getId());
        self::assertEquals('2020-04-22 11:47:27', $order->getDatetime());
        self::assertEquals(1, $order->getCurrencyId());
        self::assertEquals(1, $order->getCustomerId());
        self::assertEquals('max@mustermann.de', $order->getEmail());
        self::assertEquals(3, $order->getStatus());

        $invoiceAddress = $order->getInvoiceAddress();
        self::assertInstanceOf(AddressModel::class, $invoiceAddress);
        self::assertEquals(1, $invoiceAddress->getId());
        self::assertEquals('Max', $invoiceAddress->getPrename());
        self::assertEquals('Mustermann', $invoiceAddress->getLastname());

        $deliveryAddress = $order->getDeliveryAddress();
        self::assertInstanceOf(AddressModel::class, $deliveryAddress);
        self::assertEquals(1, $deliveryAddress->getId());

        $details = $order->getOrderdetails();
        self::assertIsArray($details);
        self::assertCount(2, $details);
        self::assertSame([1, 2], $this->getOrderdetailIds($details));
    }

    /**
     * Tests that getOrderById() returns false for a non-existent id.
     */
    public function testGetOrderByIdNotFound()
    {
        $order = $this->out->getOrderById(9999);

        self::assertFalse($order);
    }

    /**
     * Tests that getOrderBySelector() returns the matching order.
     */
    public function testGetOrderBySelector()
    {
        $invoice = $this->createAddressModel(
            0,
            1,
            'Selector',
            'Invoice',
            'Selectorstr. 1',
            '11111',
            'Selectorstadt',
            'Deutschland'
        );
        $delivery = $this->createAddressModel(
            0,
            1,
            'Selector',
            'Delivery',
            'Lieferstr. 2',
            '11111',
            'Selectorstadt',
            'Deutschland'
        );
        $details = [
            $this->createOrderdetailsModel(0, 1, 25.00, 1, 19, 0.00)
        ];

        $order = $this->createOrderModel(
            0,
            '2026-01-01 12:00:00',
            1,
            1,
            $invoice,
            $delivery,
            'max@mustermann.de',
            '',
            '2026-01-01 00:00:00',
            0,
            'test-selector-123',
            '',
            0,
            $details
        );

        $id = $this->out->save($order);

        self::assertGreaterThan(4, $id);

        $found = $this->out->getOrderBySelector('test-selector-123');

        self::assertInstanceOf(OrderModel::class, $found);
        self::assertEquals($id, $found->getId());
        self::assertEquals('test-selector-123', $found->getSelector());
        self::assertCount(1, $found->getOrderdetails());
    }

    /**
     * Tests that getOrderBySelector() returns false for a non-existent selector.
     */
    public function testGetOrderBySelectorNotFound()
    {
        $order = $this->out->getOrderBySelector('missing-selector');

        self::assertFalse($order);
    }

    /**
     * Tests that getOrdersByCustomerId() returns only orders of the given customer.
     */
    public function testGetOrdersByCustomerId()
    {
        $orders = $this->out->getOrdersByCustomerId(1);

        self::assertIsArray($orders);
        self::assertCount(1, $orders);
        self::assertEquals(1, $orders[0]->getId());

        $orders = $this->out->getOrdersByCustomerId(2);

        self::assertIsArray($orders);
        self::assertCount(1, $orders);
        self::assertEquals(2, $orders[0]->getId());

        $orders = $this->out->getOrdersByCustomerId(9999);

        self::assertIsArray($orders);
        self::assertCount(0, $orders);
    }

    /**
     * Tests inserting a new order via save().
     */
    public function testSaveInsert()
    {
        $invoice = $this->createAddressModel(
            0,
            1,
            'New',
            'Invoice',
            'Invoicestr. 1',
            '11111',
            'Newstadt',
            'Deutschland'
        );
        $delivery = $this->createAddressModel(
            0,
            1,
            'New',
            'Delivery',
            'Lieferstr. 1',
            '11111',
            'Newstadt',
            'Deutschland'
        );
        $details = [
            $this->createOrderdetailsModel(0, 1, 25.00, 1, 19, 0.00)
        ];

        $order = $this->createOrderModel(
            0,
            '2026-01-02 12:00:00',
            1,
            1,
            $invoice,
            $delivery,
            'max@mustermann.de',
            '',
            '2026-01-02 00:00:00',
            0,
            '',
            '',
            0,
            $details
        );

        $id = $this->out->save($order);

        self::assertGreaterThan(4, $id);

        $saved = $this->out->getOrderById($id);

        self::assertInstanceOf(OrderModel::class, $saved);
        self::assertEquals($id, $saved->getId());
        self::assertEquals('2026-01-02 12:00:00', $saved->getDatetime());
        self::assertEquals(1, $saved->getCurrencyId());
        self::assertEquals(1, $saved->getCustomerId());
        self::assertEquals('max@mustermann.de', $saved->getEmail());
        self::assertEquals('2026-01-02 00:00:00', $saved->getDatetimeInvoiceSent());
        self::assertEquals(0, $saved->getWillCollect());
        self::assertEquals(0, $saved->getStatus());

        self::assertInstanceOf(AddressModel::class, $saved->getInvoiceAddress());
        self::assertGreaterThan(4, $saved->getInvoiceAddress()->getId());
        self::assertInstanceOf(AddressModel::class, $saved->getDeliveryAddress());
        self::assertGreaterThan(4, $saved->getDeliveryAddress()->getId());

        $savedDetails = $saved->getOrderdetails();
        self::assertIsArray($savedDetails);
        self::assertCount(1, $savedDetails);
        self::assertInstanceOf(OrderdetailsModel::class, $savedDetails[0]);
        self::assertEquals($id, $savedDetails[0]->getOrderId());
        self::assertEquals(1, $savedDetails[0]->getItemId());
        self::assertEquals(25.00, $savedDetails[0]->getPrice());
        self::assertEquals(1, $savedDetails[0]->getQuantity());
        self::assertEquals(19, $savedDetails[0]->getTax());
        self::assertEquals(0.00, $savedDetails[0]->getShippingCosts());
    }

    /**
     * Tests updating an existing order via save().
     */
    public function testSaveUpdate()
    {
        $invoice = $this->createAddressModel(
            1,
            1,
            'Max',
            'Mustermann',
            'Musterstr. 1',
            '12345',
            'Musterstadt',
            'Deutschland'
        );
        $delivery = $this->createAddressModel(
            1,
            1,
            'Max',
            'Mustermann',
            'Musterstr. 1',
            '12345',
            'Musterstadt',
            'Deutschland'
        );
        $details = [
            $this->createOrderdetailsModel(1, 1, 25.00, 1, 19, 0.00),
            $this->createOrderdetailsModel(2, 5, 25.00, 2, 19, 0.00)
        ];

        $order = $this->createOrderModel(
            1,
            '2020-04-22 11:47:27',
            1,
            1,
            $invoice,
            $delivery,
            'max@mustermann.de',
            '',
            '2020-04-22 00:00:00',
            0,
            'updated-selector',
            'updated-confirm-code',
            2,
            $details
        );

        $id = $this->out->save($order);

        self::assertSame(1, $id);

        $updated = $this->out->getOrderById(1);

        self::assertInstanceOf(OrderModel::class, $updated);
        self::assertEquals(1, $updated->getId());
        self::assertEquals(2, $updated->getStatus());
        self::assertEquals('updated-selector', $updated->getSelector());
        self::assertEquals('updated-confirm-code', $updated->getConfirmCode());
        self::assertEquals('2020-04-22 00:00:00', $updated->getDatetimeInvoiceSent());
        self::assertCount(2, $updated->getOrderdetails());
    }

    /**
     * Tests that updateStatus() only changes the status.
     */
    public function testUpdateStatus()
    {
        $order = $this->out->getOrderById(1);

        self::assertInstanceOf(OrderModel::class, $order);
        self::assertEquals(3, $order->getStatus());

        $order->setStatus(2);
        $updatedId = $this->out->updateStatus($order);

        self::assertSame(1, $updatedId);

        $updated = $this->out->getOrderById(1);

        self::assertInstanceOf(OrderModel::class, $updated);
        self::assertEquals(2, $updated->getStatus());
        self::assertEquals(1, $updated->getCustomerId());
        self::assertEquals('max@mustermann.de', $updated->getEmail());
    }

    /**
     * Tests that updateStatus() returns null when no order is updated.
     */
    public function testUpdateStatusNoMatch()
    {
        $order = new OrderModel();
        $order
            ->setId(9999)
            ->setStatus(2);

        self::assertNull($this->out->updateStatus($order));
    }

    /**
     * Tests that delete() removes an order.
     */
    public function testDelete()
    {
        self::assertTrue($this->out->delete(1));

        self::assertFalse($this->out->getOrderById(1));

        $orders = $this->out->getOrders();

        self::assertIsArray($orders);
        self::assertCount(3, $orders);
        self::assertNotContains(1, $this->getOrderIds($orders));
    }

    /**
     * Tests that delete() on a non-existent id does not affect existing orders.
     */
    public function testDeleteNotFound()
    {
        self::assertFalse($this->out->delete(9999));

        $orders = $this->out->getOrders();

        self::assertIsArray($orders);
        self::assertCount(4, $orders);
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

    private function createOrderModel(
        int $id,
        string $datetime,
        int $currencyId,
        int $customerId,
        AddressModel $invoiceAddress,
        AddressModel $deliveryAddress,
        string $email,
        string $invoiceFilename,
        string $datetimeInvoiceSent,
        int $willCollect,
        string $selector,
        string $confirmCode,
        int $status,
        array $orderdetails = []
    ): OrderModel {
        $order = new OrderModel();
        $order
            ->setId($id)
            ->setDatetime($datetime)
            ->setCurrencyId($currencyId)
            ->setCustomerId($customerId)
            ->setInvoiceAddress($invoiceAddress)
            ->setDeliveryAddress($deliveryAddress)
            ->setEmail($email)
            ->setOrderdetails($orderdetails)
            ->setInvoiceFilename($invoiceFilename)
            ->setDatetimeInvoiceSent($datetimeInvoiceSent)
            ->setWillCollect($willCollect)
            ->setSelector($selector)
            ->setConfirmCode($confirmCode)
            ->setStatus($status);

        return $order;
    }

    private function createOrderdetailsModel(
        int $id,
        int $itemId,
        float $price,
        int $quantity,
        int $tax,
        float $shippingCosts
    ): OrderdetailsModel {
        $detail = new OrderdetailsModel();
        $detail
            ->setId($id)
            ->setOrderId(0)
            ->setItemId($itemId)
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setTax($tax)
            ->setShippingCosts($shippingCosts);

        return $detail;
    }

    private function getOrderIds(array $orders): array
    {
        $ids = [];
        foreach ($orders as $order) {
            $ids[] = $order->getId();
        }

        return $ids;
    }

    private function getOrderdetailIds(array $orderdetails): array
    {
        $ids = [];
        foreach ($orderdetails as $orderdetail) {
            $ids[] = $orderdetail->getId();
        }

        sort($ids);

        return $ids;
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
