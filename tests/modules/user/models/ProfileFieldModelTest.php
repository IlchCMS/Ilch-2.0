<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFieldModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ProfileFieldModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ProfileFieldModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ProfileFieldModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new ProfileFieldModel();
        $model->setKey('website');

        self::assertSame('website', $model->getKey());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new ProfileFieldModel();
        $model->setKey(123);

        self::assertSame('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new ProfileFieldModel();
        $model->setType(2);

        self::assertSame(2, $model->getType());
    }

    /**
     * Tests that setType() casts to int.
     */
    public function testSetTypeCastsToInt()
    {
        $model = new ProfileFieldModel();
        $model->setType('3');

        self::assertSame(3, $model->getType());
        self::assertIsInt($model->getType());
    }

    /**
     * Tests that setIcon() sets and returns the icon.
     */
    public function testSetIcon()
    {
        $model = new ProfileFieldModel();
        $model->setIcon('fa-solid fa-globe');

        self::assertSame('fa-solid fa-globe', $model->getIcon());
    }

    /**
     * Tests that setIcon() casts to string.
     */
    public function testSetIconCastsToString()
    {
        $model = new ProfileFieldModel();
        $model->setIcon(456);

        self::assertSame('456', $model->getIcon());
        self::assertIsString($model->getIcon());
    }

    /**
     * Tests that setAddition() sets and returns the addition.
     */
    public function testSetAddition()
    {
        $model = new ProfileFieldModel();
        $model->setAddition('https://www.facebook.com/');

        self::assertSame('https://www.facebook.com/', $model->getAddition());
    }

    /**
     * Tests that setOptions() sets and returns the options.
     */
    public function testSetOptions()
    {
        $model = new ProfileFieldModel();
        $model->setOptions('opt1,opt2');

        self::assertSame('opt1,opt2', $model->getOptions());
    }

    /**
     * Tests that setShow() sets and returns the show status.
     */
    public function testSetShow()
    {
        $model = new ProfileFieldModel();
        $model->setShow(0);

        self::assertSame(0, $model->getShow());
    }

    /**
     * Tests that setHidden() sets and returns the hidden status.
     */
    public function testSetHidden()
    {
        $model = new ProfileFieldModel();
        $model->setHidden(1);

        self::assertSame(1, $model->getHidden());
    }

    /**
     * Tests that setRegistration() sets and returns the registration flag.
     */
    public function testSetRegistration()
    {
        $model = new ProfileFieldModel();
        $model->setRegistration(2);

        self::assertSame(2, $model->getRegistration());
    }

    /**
     * Tests that setRegistration(0) stores zero (disabled, falsy but valid).
     */
    public function testSetRegistrationZero()
    {
        $model = new ProfileFieldModel();
        $model->setRegistration(0);

        self::assertSame(0, $model->getRegistration());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new ProfileFieldModel();
        $model->setPosition(10);

        self::assertSame(10, $model->getPosition());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ProfileFieldModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setKey('website'));
        self::assertSame($model, $model->setType(2));
        self::assertSame($model, $model->setIcon('fa-solid fa-globe'));
        self::assertSame($model, $model->setAddition('https://'));
        self::assertSame($model, $model->setOptions('a,b'));
        self::assertSame($model, $model->setShow(1));
        self::assertSame($model, $model->setHidden(0));
        self::assertSame($model, $model->setRegistration(0));
        self::assertSame($model, $model->setPosition(0));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ProfileFieldModel())
            ->setId(1)
            ->setKey('website')
            ->setType(2)
            ->setIcon('fa-solid fa-globe')
            ->setAddition('')
            ->setOptions('')
            ->setShow(1)
            ->setHidden(0)
            ->setRegistration(0)
            ->setPosition(0);

        self::assertSame(1, $model->getId());
        self::assertSame('website', $model->getKey());
        self::assertSame(2, $model->getType());
        self::assertSame('fa-solid fa-globe', $model->getIcon());
        self::assertSame('', $model->getAddition());
        self::assertSame('', $model->getOptions());
        self::assertSame(1, $model->getShow());
        self::assertSame(0, $model->getHidden());
        self::assertSame(0, $model->getRegistration());
        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests default values: null for nullable properties,
     * schema defaults for the rest.
     */
    public function testDefaultValues()
    {
        $model = new ProfileFieldModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getKey());
        self::assertNull($model->getType());
        self::assertSame('', $model->getIcon());
        self::assertSame('', $model->getAddition());
        self::assertSame('', $model->getOptions());
        self::assertSame(1, $model->getShow());
        self::assertSame(0, $model->getHidden());
        self::assertSame(0, $model->getRegistration());
        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ProfileFieldModel();
        $model->setId(1)->setKey('website')->setType(2);

        $model->setId(2)->setKey('facebook')->setType(1);

        self::assertSame(2, $model->getId());
        self::assertSame('facebook', $model->getKey());
        self::assertSame(1, $model->getType());
    }
}
