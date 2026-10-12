<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\NotificationPermission as NotificationPermissionModel;

class NotificationPermissionModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new NotificationPermissionModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() coerces a numeric string to int.
     */
    public function testSetIdCoercesToInt()
    {
        $model = new NotificationPermissionModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new NotificationPermissionModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new NotificationPermissionModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() coerces a numeric string to int.
     */
    public function testSetUserIdCoercesToInt()
    {
        $model = new NotificationPermissionModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new NotificationPermissionModel();
        $model->setModule('user');

        self::assertSame('user', $model->getModule());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new NotificationPermissionModel();
        $model->setType('adminModuleUpdatesAvailable');

        self::assertSame('adminModuleUpdatesAvailable', $model->getType());
    }

    /**
     * Tests that setGranted() sets and returns the granted state.
     */
    public function testSetGranted()
    {
        $model = new NotificationPermissionModel();
        $model->setGranted(true);

        self::assertSame(true, $model->getGranted());
        self::assertIsBool($model->getGranted());
    }

    /**
     * Tests that setGranted() coerces int values to bool.
     */
    public function testSetGrantedCoercesInt()
    {
        $model = new NotificationPermissionModel();
        $model->setGranted(1);

        self::assertSame(true, $model->getGranted());

        $model->setGranted(0);

        self::assertSame(false, $model->getGranted());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new NotificationPermissionModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setModule('user'));
        self::assertSame($model, $model->setType('type'));
        self::assertSame($model, $model->setGranted(true));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new NotificationPermissionModel())
            ->setId(1)
            ->setUserId(2)
            ->setModule('user')
            ->setType('notification')
            ->setGranted(true);

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('user', $model->getModule());
        self::assertSame('notification', $model->getType());
        self::assertSame(true, $model->getGranted());
    }

    /**
     * Tests that default values are null / false.
     */
    public function testDefaultValues()
    {
        $model = new NotificationPermissionModel();

        self::assertNull($model->getId());
        self::assertNull($model->getUserId());
        self::assertNull($model->getModule());
        self::assertNull($model->getType());
        self::assertSame(false, $model->getGranted());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new NotificationPermissionModel();
        $model->setId(1)->setUserId(1)->setModule('user')->setType('old')->setGranted(false);

        $model->setId(2)->setUserId(2)->setModule('page')->setType('new')->setGranted(true);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('page', $model->getModule());
        self::assertSame('new', $model->getType());
        self::assertSame(true, $model->getGranted());
    }
}
