<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

class OrderdetailsTest extends DatabaseTestCase
{
    /**
     * @var Orderdetails
     */
    protected Orderdetails $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new Orderdetails();
    }

    /**
     * Tests that getOrderdetailsBy() returns all order details from the sample data.
     */
    public function testGetOrderdetailsBy()
    {
        $orderdetails = $this->out->getOrderdetailsBy();

        self::assertIsArray($orderdetails);
        self::assertCount(8, $orderdetails);
        self::assertInstanceOf(OrderdetailsModel::class, $orderdetails[0]);
    }

    /**
     * Tests that getOrderdetailsById() returns the correct fields for the first sample order detail.
     */
    public function testGetOrderdetailsByIdFields()
    {
        $orderdetail = $this->out->getOrderdetailsById(1);

        self::assertInstanceOf(OrderdetailsModel::class, $orderdetail);
        self::assertEquals(1, $orderdetail->getId());
        self::assertEquals(1, $orderdetail->getOrderId());
        self::assertEquals(1, $orderdetail->getItemId());
        self::assertEqualsWithDelta(25.00, $orderdetail->getPrice(), 0.001);
        self::assertEquals(1, $orderdetail->getQuantity());
        self::assertEquals(19, $orderdetail->getTax());
        self::assertEqualsWithDelta(0.00, $orderdetail->getShippingCosts(), 0.001);
    }

    /**
     * Tests that getOrderdetailsById() returns the correct fields for another sample order detail.
     */
    public function testGetOrderdetailsByIdSecond()
    {
        $orderdetail = $this->out->getOrderdetailsById(8);

        self::assertInstanceOf(OrderdetailsModel::class, $orderdetail);
        self::assertEquals(8, $orderdetail->getId());
        self::assertEquals(4, $orderdetail->getOrderId());
        self::assertEquals(7, $orderdetail->getItemId());
        self::assertEqualsWithDelta(65.90, $orderdetail->getPrice(), 0.001);
        self::assertEquals(1, $orderdetail->getQuantity());
        self::assertEquals(19, $orderdetail->getTax());
        self::assertEqualsWithDelta(0.00, $orderdetail->getShippingCosts(), 0.001);
    }

    /**
     * Tests that getOrderdetailsBy() accepts a where array.
     */
    public function testGetOrderdetailsByWhere()
    {
        $orderdetails = $this->out->getOrderdetailsBy(['itemId' => 5]);

        self::assertIsArray($orderdetails);
        self::assertCount(2, $orderdetails);
    }

    /**
     * Tests that getOrderdetailsBy() returns an empty array when no order details match.
     */
    public function testGetOrderdetailsByNoMatch()
    {
        $orderdetails = $this->out->getOrderdetailsBy(['orderId' => 9999]);

        self::assertIsArray($orderdetails);
        self::assertCount(0, $orderdetails);
    }

    /**
     * Tests that getOrderdetailsByOrderId() returns all order details for an order.
     */
    public function testGetOrderdetailsByOrderId()
    {
        $orderdetails = $this->out->getOrderdetailsByOrderId(1);

        self::assertIsArray($orderdetails);
        self::assertCount(2, $orderdetails);

        foreach ($orderdetails as $orderdetail) {
            self::assertEquals(1, $orderdetail->getOrderId());
        }
    }

    /**
     * Tests that getOrderdetailsByOrderId() returns an empty array when the order has no details.
     */
    public function testGetOrderdetailsByOrderIdNoDetails()
    {
        $orderdetails = $this->out->getOrderdetailsByOrderId(9999);

        self::assertIsArray($orderdetails);
        self::assertCount(0, $orderdetails);
    }

    /**
     * Tests that getOrderdetailsById() returns false for a non-existent id.
     */
    public function testGetOrderdetailsByIdNotFound()
    {
        $orderdetail = $this->out->getOrderdetailsById(9999);

        self::assertFalse($orderdetail);
    }

    /**
     * Tests inserting multiple new order details via save().
     */
    public function testSaveInsertMultiple()
    {
        $first = new OrderdetailsModel();
        $first->setId(0)
            ->setOrderId(1)
            ->setItemId(1)
            ->setPrice(10.00)
            ->setQuantity(1)
            ->setTax(19)
            ->setShippingCosts(0.00);

        $second = new OrderdetailsModel();
        $second->setId(0)
            ->setOrderId(1)
            ->setItemId(2)
            ->setPrice(20.00)
            ->setQuantity(2)
            ->setTax(19)
            ->setShippingCosts(1.00);

        $affected = $this->out->save([$first, $second]);

        self::assertSame(2, $affected);

        $orderdetails = $this->out->getOrderdetailsByOrderId(1);
        self::assertCount(4, $orderdetails);

        $prices = [];
        foreach ($orderdetails as $orderdetail) {
            $prices[] = $orderdetail->getPrice();
        }

        self::assertContains(10.00, $prices);
        self::assertContains(20.00, $prices);
    }

    /**
     * Tests that save() does not insert when an existing id is present.
     */
    public function testSaveWithExistingIdDoesNotInsert()
    {
        $orderdetail = new OrderdetailsModel();
        $orderdetail->setId(1)
            ->setOrderId(1)
            ->setItemId(1)
            ->setPrice(25.00)
            ->setQuantity(1)
            ->setTax(19)
            ->setShippingCosts(0.00);

        $affected = $this->out->save([$orderdetail]);

        self::assertSame(0, $affected);

        $orderdetails = $this->out->getOrderdetailsByOrderId(1);
        self::assertCount(2, $orderdetails);
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
