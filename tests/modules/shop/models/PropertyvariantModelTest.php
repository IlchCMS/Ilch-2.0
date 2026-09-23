<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Propertyvariant as PropertyvariantModel;

class PropertyvariantModelTest extends TestCase
{
    /**
     * Tests that the default id is null.
     */
    public function testDefaultIdNull(): void
    {
        $model = new PropertyvariantModel();

        self::assertNull($model->getId());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new PropertyvariantModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new PropertyvariantModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setId() accepts null.
     */
    public function testSetIdNull(): void
    {
        $model = new PropertyvariantModel();
        $model->setId(null);

        self::assertNull($model->getId());
    }

    /**
     * Tests that setItemId() sets the item id.
     */
    public function testSetItemId(): void
    {
        $model = new PropertyvariantModel();
        $model->setItemId(1);

        self::assertSame(1, $model->getItemId());
    }

    /**
     * Tests that setItemVariantId() sets the item variant id.
     */
    public function testSetItemVariantId(): void
    {
        $model = new PropertyvariantModel();
        $model->setItemVariantId(2);

        self::assertSame(2, $model->getItemVariantId());
    }

    /**
     * Tests that setPropertyId() sets the property id.
     */
    public function testSetPropertyId(): void
    {
        $model = new PropertyvariantModel();
        $model->setPropertyId(3);

        self::assertSame(3, $model->getPropertyId());
    }

    /**
     * Tests that setValueId() sets the value id.
     */
    public function testSetValueId(): void
    {
        $model = new PropertyvariantModel();
        $model->setValueId(4);

        self::assertSame(4, $model->getValueId());
    }

    /**
     * Tests that Propertyvariant setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new PropertyvariantModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setItemId(2));
        self::assertSame($model, $model->setItemVariantId(3));
        self::assertSame($model, $model->setPropertyId(4));
        self::assertSame($model, $model->setValueId(5));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new PropertyvariantModel())
            ->setId(1)
            ->setItemId(2)
            ->setItemVariantId(3)
            ->setPropertyId(4)
            ->setValueId(5);

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getItemId());
        self::assertSame(3, $model->getItemVariantId());
        self::assertSame(4, $model->getPropertyId());
        self::assertSame(5, $model->getValueId());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new PropertyvariantModel();

        $model
            ->setId(1)
            ->setItemId(1)
            ->setItemVariantId(1)
            ->setPropertyId(1)
            ->setValueId(1);

        $model
            ->setId(2)
            ->setItemId(2)
            ->setItemVariantId(2)
            ->setPropertyId(2)
            ->setValueId(2);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getItemId());
        self::assertSame(2, $model->getItemVariantId());
        self::assertSame(2, $model->getPropertyId());
        self::assertSame(2, $model->getValueId());
    }
}
