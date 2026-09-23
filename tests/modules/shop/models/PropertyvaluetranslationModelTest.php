<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Propertyvaluetranslation as PropertyvaluetranslationModel;

class PropertyvaluetranslationModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new PropertyvaluetranslationModel();

        self::assertNull($model->getId());
        self::assertNull($model->getValueId());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getText());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setValueId() sets the value id.
     */
    public function testSetValueId(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setValueId(7);

        self::assertSame(7, $model->getValueId());
    }

    /**
     * Tests that setValueId() accepts null.
     */
    public function testSetValueIdNull(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setValueId(null);

        self::assertNull($model->getValueId());
    }

    /**
     * Tests that setLocale() sets the locale.
     */
    public function testSetLocale(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setText() sets the text.
     */
    public function testSetText(): void
    {
        $model = new PropertyvaluetranslationModel();
        $model->setText('Groß');

        self::assertSame('Groß', $model->getText());
    }

    /**
     * Tests that chainable setters return the model.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new PropertyvaluetranslationModel();

        self::assertSame($model, $model->setValueId(1));
        self::assertSame($model, $model->setLocale('en_EN'));
        self::assertSame($model, $model->setText('Large'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new PropertyvaluetranslationModel())
            ->setValueId(2)
            ->setLocale('de_DE')
            ->setText('Groß');

        $model->setId(3);

        self::assertSame(3, $model->getId());
        self::assertSame(2, $model->getValueId());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('Groß', $model->getText());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new PropertyvaluetranslationModel();

        $model->setId(1);
        $model->setValueId(1);
        $model->setLocale('old');
        $model->setText('Old');

        $model->setId(2);
        $model->setValueId(2);
        $model->setLocale('new');
        $model->setText('New');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getValueId());
        self::assertSame('new', $model->getLocale());
        self::assertSame('New', $model->getText());
    }
}
