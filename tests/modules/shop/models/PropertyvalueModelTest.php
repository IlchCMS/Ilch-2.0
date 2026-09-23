<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Propertyvalue as PropertyvalueModel;

class PropertyvalueModelTest extends TestCase
{
    /**
     * Tests that default values are null, zero, or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new PropertyvalueModel();

        self::assertNull($model->getId());
        self::assertNull($model->getPropertyId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getValue());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new PropertyvalueModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new PropertyvalueModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setPropertyId() sets the property id.
     */
    public function testSetPropertyId(): void
    {
        $model = new PropertyvalueModel();
        $model->setPropertyId(7);

        self::assertSame(7, $model->getPropertyId());
    }

    /**
     * Tests that setPropertyId() accepts null.
     */
    public function testSetPropertyIdNull(): void
    {
        $model = new PropertyvalueModel();
        $model->setPropertyId(null);

        self::assertNull($model->getPropertyId());
    }

    /**
     * Tests that setValue() sets the value.
     */
    public function testSetValue(): void
    {
        $model = new PropertyvalueModel();
        $model->setValue('Large');

        self::assertSame('Large', $model->getValue());
    }

    /**
     * Tests that setPosition() sets the position.
     */
    public function testSetPosition(): void
    {
        $model = new PropertyvalueModel();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setPosition() accepts null.
     */
    public function testSetPositionNull(): void
    {
        $model = new PropertyvalueModel();
        $model->setPosition(null);

        self::assertNull($model->getPosition());
    }

    /**
     * Tests that chainable setters return the model.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new PropertyvalueModel();

        self::assertSame($model, $model->setPropertyId(1));
        self::assertSame($model, $model->setValue('Small'));
        self::assertSame($model, $model->setPosition(2));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new PropertyvalueModel())
            ->setPropertyId(1)
            ->setValue('Medium')
            ->setPosition(2);

        $model->setId(3);

        self::assertSame(3, $model->getId());
        self::assertSame(1, $model->getPropertyId());
        self::assertSame('Medium', $model->getValue());
        self::assertSame(2, $model->getPosition());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new PropertyvalueModel();

        $model->setId(1);
        $model->setPropertyId(1);
        $model->setValue('Old');
        $model->setPosition(1);

        $model->setId(2);
        $model->setPropertyId(2);
        $model->setValue('New');
        $model->setPosition(2);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getPropertyId());
        self::assertSame('New', $model->getValue());
        self::assertSame(2, $model->getPosition());
    }
}
