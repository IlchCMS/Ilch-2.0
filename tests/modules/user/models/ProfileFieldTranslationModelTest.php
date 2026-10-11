<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\ProfileFieldTranslation as ProfileFieldTranslationModel;

class ProfileFieldTranslationModelTest extends TestCase
{
    /**
     * Tests that setFieldId() sets and returns the field id.
     */
    public function testSetFieldId()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId(5);

        self::assertSame(5, $model->getFieldId());
    }

    /**
     * Tests that setFieldId() casts to int.
     */
    public function testSetFieldIdCastsToInt()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId('42');

        self::assertSame(42, $model->getFieldId());
        self::assertIsInt($model->getFieldId());
    }

    /**
     * Tests that setLocale() sets and returns the locale.
     */
    public function testSetLocale()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setLocale('en_EN');

        self::assertSame('en_EN', $model->getLocale());
    }

    /**
     * Tests that setLocale() casts to string.
     */
    public function testSetLocaleCastsToString()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setLocale(123);

        self::assertSame('123', $model->getLocale());
        self::assertIsString($model->getLocale());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setName('Website');

        self::assertSame('Website', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setName(456);

        self::assertSame('456', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ProfileFieldTranslationModel();

        self::assertSame($model, $model->setFieldId(1));
        self::assertSame($model, $model->setLocale('de_DE'));
        self::assertSame($model, $model->setName('Webseite'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ProfileFieldTranslationModel())
            ->setFieldId(1)
            ->setLocale('en_EN')
            ->setName('Website');

        self::assertSame(1, $model->getFieldId());
        self::assertSame('en_EN', $model->getLocale());
        self::assertSame('Website', $model->getName());
    }

    /**
     * Tests that default values are null for the field id
     * (0 is not a valid field id) and empty strings
     * for locale and name.
     */
    public function testDefaultValues()
    {
        $model = new ProfileFieldTranslationModel();

        self::assertNull($model->getFieldId());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getName());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ProfileFieldTranslationModel();
        $model->setFieldId(1)->setLocale('de_DE')->setName('Webseite');

        $model->setFieldId(2)->setLocale('en_EN')->setName('Website');

        self::assertSame(2, $model->getFieldId());
        self::assertSame('en_EN', $model->getLocale());
        self::assertSame('Website', $model->getName());
    }
}
