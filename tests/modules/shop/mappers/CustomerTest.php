<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Models\Customer as CustomerModel;

class CustomerTest extends DatabaseTestCase
{
    /**
     * @var Customer
     */
    protected Customer $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new Customer();
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
