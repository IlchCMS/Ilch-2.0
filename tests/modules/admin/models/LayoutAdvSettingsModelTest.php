<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

/**
 * Tests the LayoutAdvSettings model class.
 *
 * @package ilch_phpunit
 */
class LayoutAdvSettingsModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new LayoutAdvSettingsModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setLayoutKey() sets and returns the layout key.
     */
    public function testSetLayoutKey()
    {
        $model = new LayoutAdvSettingsModel();
        $model->setLayoutKey('testLayoutKey');

        self::assertSame('testLayoutKey', $model->getLayoutKey());
    }

    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new LayoutAdvSettingsModel();
        $model->setKey('testKey');

        self::assertSame('testKey', $model->getKey());
    }

    /**
     * Tests that setValue() sets and returns the value.
     */
    public function testSetValue()
    {
        $model = new LayoutAdvSettingsModel();
        $model->setValue('testValue');

        self::assertSame('testValue', $model->getValue());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new LayoutAdvSettingsModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setLayoutKey('testLayoutKey'));
        self::assertSame($model, $model->setKey('testKey'));
        self::assertSame($model, $model->setValue('testValue'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new LayoutAdvSettingsModel())
            ->setId(3)
            ->setLayoutKey('testLayoutKey')
            ->setKey('testKey')
            ->setValue('testValue');

        self::assertSame(3, $model->getId());
        self::assertSame('testLayoutKey', $model->getLayoutKey());
        self::assertSame('testKey', $model->getKey());
        self::assertSame('testValue', $model->getValue());
    }

    /**
     * Tests that default values are 0 for id and empty string for the other fields.
     */
    public function testDefaultValues()
    {
        $model = new LayoutAdvSettingsModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getLayoutKey());
        self::assertSame('', $model->getKey());
        self::assertSame('', $model->getValue());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new LayoutAdvSettingsModel();
        $model->setId(1)->setLayoutKey('oldLayout')->setKey('oldKey')->setValue('oldValue');

        $model->setId(2)->setLayoutKey('newLayout')->setKey('newKey')->setValue('newValue');

        self::assertSame(2, $model->getId());
        self::assertSame('newLayout', $model->getLayoutKey());
        self::assertSame('newKey', $model->getKey());
        self::assertSame('newValue', $model->getValue());
    }
}
