<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;

/**
 * Tests the NotificationPermission model class.
 *
 * @package ilch_phpunit
 */
class NotificationPermissionModelTest extends TestCase
{
    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new NotificationPermissionModel();
        $model->setModule('article');

        self::assertSame('article', $model->getModule());
    }

    /**
     * Tests that setGranted(true) stores 1.
     */
    public function testSetGrantedTrue()
    {
        $model = new NotificationPermissionModel();
        $model->setGranted(true);

        self::assertSame(1, $model->getGranted());
        self::assertIsInt($model->getGranted());
    }

    /**
     * Tests that setGranted(false) stores 0.
     */
    public function testSetGrantedFalse()
    {
        $model = new NotificationPermissionModel();
        $model->setGranted(false);

        self::assertSame(0, $model->getGranted());
        self::assertIsInt($model->getGranted());
    }

    /**
     * Tests that setLimit() sets and returns the limit.
     */
    public function testSetLimit()
    {
        $model = new NotificationPermissionModel();
        $model->setLimit(5);

        self::assertSame(5, $model->getLimit());
    }

    /**
     * Tests that setLimit() casts to int.
     */
    public function testSetLimitCastsToInt()
    {
        $model = new NotificationPermissionModel();
        $model->setLimit('3');

        self::assertSame(3, $model->getLimit());
        self::assertIsInt($model->getLimit());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new NotificationPermissionModel();

        self::assertNull($model->getModule());
        self::assertNull($model->getGranted());
        self::assertNull($model->getLimit());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new NotificationPermissionModel();
        $model->setModule('article');
        $model->setGranted(1);
        $model->setLimit(5);

        $model->setModule('awards');
        $model->setGranted(0);
        $model->setLimit(3);

        self::assertSame('awards', $model->getModule());
        self::assertSame(0, $model->getGranted());
        self::assertSame(3, $model->getLimit());
    }
}
