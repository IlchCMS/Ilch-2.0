<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Usermenu as UsermenuModel;

class UsermenuModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new UsermenuModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new UsermenuModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new UsermenuModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new UsermenuModel();
        $model->setKey('user/panel/index');

        self::assertSame('user/panel/index', $model->getKey());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new UsermenuModel();
        $model->setKey(123);

        self::assertSame('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setIcon() sets and returns the icon.
     */
    public function testSetIcon()
    {
        $model = new UsermenuModel();
        $model->setIcon('fa-solid fa-house');

        self::assertSame('fa-solid fa-house', $model->getIcon());
    }

    /**
     * Tests that setIcon() casts to string.
     */
    public function testSetIconCastsToString()
    {
        $model = new UsermenuModel();
        $model->setIcon(456);

        self::assertSame('456', $model->getIcon());
        self::assertIsString($model->getIcon());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new UsermenuModel();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setPosition() casts to int.
     */
    public function testSetPositionCastsToInt()
    {
        $model = new UsermenuModel();
        $model->setPosition('5');

        self::assertSame(5, $model->getPosition());
        self::assertIsInt($model->getPosition());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new UsermenuModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setKey('user/panel/index'));
        self::assertSame($model, $model->setIcon('fa-solid fa-house'));
        self::assertSame($model, $model->setPosition(1));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new UsermenuModel())
            ->setId(1)
            ->setKey('user/panel/index')
            ->setIcon('fa-solid fa-house')
            ->setPosition(1);

        self::assertSame(1, $model->getId());
        self::assertSame('user/panel/index', $model->getKey());
        self::assertSame('fa-solid fa-house', $model->getIcon());
        self::assertSame(1, $model->getPosition());
    }

    /**
     * Tests that default values are null for all properties.
     */
    public function testDefaultValues()
    {
        $model = new UsermenuModel();

        self::assertNull($model->getId());
        self::assertNull($model->getKey());
        self::assertNull($model->getIcon());
        self::assertNull($model->getPosition());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new UsermenuModel();
        $model->setId(1)->setKey('user/panel/index');

        $model->setId(2)->setKey('user/panel/settings');

        self::assertSame(2, $model->getId());
        self::assertSame('user/panel/settings', $model->getKey());
    }
}
