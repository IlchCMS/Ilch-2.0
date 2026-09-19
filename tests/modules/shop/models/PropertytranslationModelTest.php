<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Propertytranslation as PropertytranslationModel;

class PropertytranslationModelTest extends TestCase
{
    /**
     * Tests that default values are null or empty.
     */
    public function testDefaultValues(): void
    {
        $model = new PropertytranslationModel();

        self::assertNull($model->getId());
        self::assertNull($model->getPropertyId());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getText());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new PropertytranslationModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new PropertytranslationModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setPropertyId() sets the property id.
     */
    public function testSetPropertyId(): void
    {
        $model = new PropertytranslationModel();
        $model->setPropertyId(7);

        self::assertSame(7, $model->getPropertyId());
    }

    /**
     * Tests that setPropertyId() accepts null.
     */
    public function testSetPropertyIdNull(): void
    {
        $model = new PropertytranslationModel();
        $model->setPropertyId(null);

        self::assertNull($model->getPropertyId());
    }

    /**
     * Tests that setLocale() sets the locale.
     */
    public function testSetLocale(): void
    {
        $model = new PropertytranslationModel();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setText() sets the text.
     */
    public function testSetText(): void
    {
        $model = new PropertytranslationModel();
        $model->setText('Größe');

        self::assertSame('Größe', $model->getText());
    }

    /**
     * Tests that chainable setters return the model.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new PropertytranslationModel();

        self::assertSame($model, $model->setPropertyId(1));
        self::assertSame($model, $model->setLocale('en_EN'));
        self::assertSame($model, $model->setText('Size'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new PropertytranslationModel())
            ->setPropertyId(2)
            ->setLocale('de_DE')
            ->setText('Größe');

        $model->setId(3);

        self::assertSame(3, $model->getId());
        self::assertSame(2, $model->getPropertyId());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('Größe', $model->getText());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new PropertytranslationModel();

        $model->setId(1);
        $model->setPropertyId(1);
        $model->setLocale('old');
        $model->setText('Old');

        $model->setId(2);
        $model->setPropertyId(2);
        $model->setLocale('new');
        $model->setText('New');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getPropertyId());
        self::assertSame('new', $model->getLocale());
        self::assertSame('New', $model->getText());
    }
}
