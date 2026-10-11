<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\ProfileFieldContent as ProfileFieldContentModel;

class ProfileFieldContentModelTest extends TestCase
{
    /**
     * Tests that setFieldId() sets and returns the field id.
     */
    public function testSetFieldId()
    {
        $model = new ProfileFieldContentModel();
        $model->setFieldId(5);

        self::assertSame(5, $model->getFieldId());
    }

    /**
     * Tests that setFieldId() casts to int.
     */
    public function testSetFieldIdCastsToInt()
    {
        $model = new ProfileFieldContentModel();
        $model->setFieldId('42');

        self::assertSame(42, $model->getFieldId());
        self::assertIsInt($model->getFieldId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new ProfileFieldContentModel();
        $model->setUserId(7);

        self::assertSame(7, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new ProfileFieldContentModel();
        $model->setUserId('13');

        self::assertSame(13, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setValue() sets and returns the value.
     */
    public function testSetValue()
    {
        $model = new ProfileFieldContentModel();
        $model->setValue('https://example.com');

        self::assertSame('https://example.com', $model->getValue());
    }

    /**
     * Tests that setValue() casts to string.
     */
    public function testSetValueCastsToString()
    {
        $model = new ProfileFieldContentModel();
        $model->setValue(456);

        self::assertSame('456', $model->getValue());
        self::assertIsString($model->getValue());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ProfileFieldContentModel();

        self::assertSame($model, $model->setFieldId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setValue('test'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ProfileFieldContentModel())
            ->setFieldId(1)
            ->setUserId(2)
            ->setValue('https://example.com');

        self::assertSame(1, $model->getFieldId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('https://example.com', $model->getValue());
    }

    /**
     * Tests that default values are null for the ids
     * (0 is not a valid user/field id) and an empty
     * string for the value.
     */
    public function testDefaultValues()
    {
        $model = new ProfileFieldContentModel();

        self::assertNull($model->getFieldId());
        self::assertNull($model->getUserId());
        self::assertSame('', $model->getValue());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ProfileFieldContentModel();
        $model->setFieldId(1)->setUserId(2)->setValue('old');

        $model->setFieldId(3)->setUserId(4)->setValue('new');

        self::assertSame(3, $model->getFieldId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('new', $model->getValue());
    }
}
