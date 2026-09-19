<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

class OrderdetailsModelTest extends TestCase
{
    /**
     * Tests that default values are null or zero.
     */
    public function testDefaultValues(): void
    {
        $model = new OrderdetailsModel();

        self::assertNull($model->getId());
        self::assertSame(0, $model->getOrderId());
        self::assertSame(0, $model->getItemId());
        self::assertSame(0.0, $model->getPrice());
        self::assertSame(0, $model->getQuantity());
        self::assertSame(0, $model->getTax());
        self::assertSame(0.0, $model->getShippingCosts());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new OrderdetailsModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new OrderdetailsModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setOrderId() sets the order id.
     */
    public function testSetOrderId(): void
    {
        $model = new OrderdetailsModel();
        $model->setOrderId(1);

        self::assertSame(1, $model->getOrderId());
    }

    /**
     * Tests that setItemId() sets the item id.
     */
    public function testSetItemId(): void
    {
        $model = new OrderdetailsModel();
        $model->setItemId(2);

        self::assertSame(2, $model->getItemId());
    }

    /**
     * Tests that setPrice() sets the price.
     */
    public function testSetPrice(): void
    {
        $model = new OrderdetailsModel();
        $model->setPrice(25.0);

        self::assertSame(25.0, $model->getPrice());
    }

    /**
     * Tests that setQuantity() sets the quantity.
     */
    public function testSetQuantity(): void
    {
        $model = new OrderdetailsModel();
        $model->setQuantity(2);

        self::assertSame(2, $model->getQuantity());
    }

    /**
     * Tests that setTax() sets the tax.
     */
    public function testSetTax(): void
    {
        $model = new OrderdetailsModel();
        $model->setTax(19);

        self::assertSame(19, $model->getTax());
    }

    /**
     * Tests that setShippingCosts() sets the shipping costs.
     */
    public function testSetShippingCosts(): void
    {
        $model = new OrderdetailsModel();
        $model->setShippingCosts(5.50);

        self::assertSame(5.50, $model->getShippingCosts());
    }

    /**
     * Tests that Orderdetails setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new OrderdetailsModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setOrderId(2));
        self::assertSame($model, $model->setItemId(3));
        self::assertSame($model, $model->setPrice(4.50));
        self::assertSame($model, $model->setQuantity(5));
        self::assertSame($model, $model->setTax(19));
        self::assertSame($model, $model->setShippingCosts(6.50));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new OrderdetailsModel())
            ->setId(1)
            ->setOrderId(2)
            ->setItemId(3)
            ->setPrice(25.00)
            ->setQuantity(1)
            ->setTax(19)
            ->setShippingCosts(0.00);

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getOrderId());
        self::assertSame(3, $model->getItemId());
        self::assertSame(25.00, $model->getPrice());
        self::assertSame(1, $model->getQuantity());
        self::assertSame(19, $model->getTax());
        self::assertSame(0.00, $model->getShippingCosts());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new OrderdetailsModel();

        $model
            ->setId(1)
            ->setOrderId(1)
            ->setItemId(1)
            ->setPrice(10.00)
            ->setQuantity(1)
            ->setTax(19)
            ->setShippingCosts(10.00);

        $model
            ->setId(2)
            ->setOrderId(2)
            ->setItemId(2)
            ->setPrice(20.00)
            ->setQuantity(2)
            ->setTax(19)
            ->setShippingCosts(20.00);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getOrderId());
        self::assertSame(2, $model->getItemId());
        self::assertSame(20.00, $model->getPrice());
        self::assertSame(2, $model->getQuantity());
        self::assertSame(19, $model->getTax());
        self::assertSame(20.00, $model->getShippingCosts());
    }
}
