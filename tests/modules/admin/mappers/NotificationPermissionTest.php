<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the NotificationPermission mapper class.
 *
 * @package ilch_phpunit
 */
class NotificationPermissionTest extends DatabaseTestCase
{
    /**
     * @var NotificationPermissionMapper
     */
    protected NotificationPermission $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new NotificationPermissionMapper();
    }

    /**
     * Tests if getPermissions() returns all permissions from the database.
     *
     */
    public function testGetPermissions()
    {
        self::assertCount(2, $this->out->getPermissions());
    }

    /**
     * Tests if getPermissions() returns the permissions ordered by module (ASC).
     *
     */
    public function testGetPermissionsOrdered()
    {
        $permissions = $this->out->getPermissions();

        self::assertCount(2, $permissions);
        self::assertInstanceOf(NotificationPermissionModel::class, $permissions[0]);
        self::assertSame('article', $permissions[0]->getModule());
        self::assertSame('awards', $permissions[1]->getModule());
    }

    /**
     * Tests if getPermissions() returns an empty array if there are no permissions.
     *
     */
    public function testGetPermissionsEmpty()
    {
        $this->out->deletePermissionOfModule('article');
        $this->out->deletePermissionOfModule('awards');

        self::assertEmpty($this->out->getPermissions());
    }

    /**
     * Tests if getPermissionOfModule() returns the permission of an existing module.
     *
     */
    public function testGetPermissionOfModule()
    {
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        self::assertNotNull($notificationPermissionModel);
        self::assertSame('article', $notificationPermissionModel->getModule());
        self::assertEquals(1, $notificationPermissionModel->getGranted());
        self::assertEquals(5, $notificationPermissionModel->getLimit());
    }

    /**
     * Tests if getPermissionOfModule() returns null if there is no permission for a module.
     *
     */
    public function testGetPermissionOfModuleNotExisting()
    {
        self::assertNull($this->out->getPermissionOfModule('xyzmodul'));
    }

    /**
     * Tests if updatePermissionOfModule() successfully updates the NotificationPermissionModel.
     *
     */
    public function testUpdatePermissionOfModule()
    {
        $notificationPermissionModel = new NotificationPermissionModel();
        $notificationPermissionModel->setModule('article');
        $notificationPermissionModel->setGranted(0);
        $notificationPermissionModel->setLimit(3);

        $this->out->updatePermissionOfModule($notificationPermissionModel);
        self::assertEquals($notificationPermissionModel, $this->out->getPermissionOfModule('article'));
    }

    /**
     * Tests if updatePermissionGrantedOfModule() successfully updates the granted value of the NotificationPermissionModel.
     *
     */
    public function testUpdatePermissionGrantedOfModule()
    {
        $this->out->updatePermissionGrantedOfModule('article', false);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        self::assertEquals(0, $notificationPermissionModel->getGranted());
    }

    /**
     * Tests if updatePermissionGrantedOfModule() successfully updates the granted value to true.
     *
     */
    public function testUpdatePermissionGrantedOfModuleTrue()
    {
        $this->out->updatePermissionGrantedOfModule('article', true);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        self::assertEquals(1, $notificationPermissionModel->getGranted());
    }

    /**
     * Tests if updateLimitOfModule() successfully updates the limit value of the NotificationPermissionModel.
     *
     */
    public function testUpdateLimitOfModule()
    {
        $this->out->updateLimitOfModule('article', 3);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        self::assertEquals(3, $notificationPermissionModel->getLimit());
    }

    /**
     * Tests if updateLimitOfModule() successfully returns the maximum value of limit (UNSIGNED TINYINT) if one
     * tries to set it to a bigger value.
     *
     */
    public function testUpdateLimitOfModuleInvalidLimit()
    {
        $this->out->updateLimitOfModule('article', 300);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        // If the limit is bigger than 255 (UNSIGNED TINYINT) then it gets set to the maximum of 255.
        self::assertEquals(255, $notificationPermissionModel->getLimit());
    }

    /**
     * Tests if updateLimitOfModule() successfully returns the minimum value of limit (UNSIGNED TINYINT) if one
     * tries to set it to a negative value.
     *
     */
    public function testUpdateLimitOfModuleInvalidLimitNegative()
    {
        $this->out->updateLimitOfModule('article', -1);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        // If the limit is negative (UNSIGNED) then it gets set to 0.
        self::assertEquals(0, $notificationPermissionModel->getLimit());
    }

    /**
     * Tests if addPermissionForModule() successfully adds a permission for a module.
     *
     */
    public function testAddPermissionForModule()
    {
        $notificationPermissionModel = new NotificationPermissionModel();
        $notificationPermissionModel->setModule('away');
        $notificationPermissionModel->setGranted(1);
        $notificationPermissionModel->setLimit(5);

        $this->out->addPermissionForModule($notificationPermissionModel);
        self::assertEquals($notificationPermissionModel, $this->out->getPermissionOfModule('away'));
    }

    /**
     * Tests if addPermissionForModule() does not add a duplicate permission if one already exists.
     *
     */
    public function testAddPermissionForModuleDuplicate()
    {
        $notificationPermissionModel = new NotificationPermissionModel();
        $notificationPermissionModel->setModule('guestbook');
        $notificationPermissionModel->setGranted(1);
        $notificationPermissionModel->setLimit(5);

        $this->out->addPermissionForModule($notificationPermissionModel);
        $this->out->addPermissionForModule($notificationPermissionModel);

        self::assertCount(3, $this->out->getPermissions());
        self::assertSame('guestbook', $this->out->getPermissionOfModule('guestbook')->getModule());
    }

    /**
     * Tests if deletePermissionOfModule() successfully deletes a permission of a module.
     *
     */
    public function testDeletePermissionOfModule()
    {
        $this->out->deletePermissionOfModule('article');
        self::assertNull($this->out->getPermissionOfModule('article'));
    }

    /**
     * Tests if deletePermissionOfModule() doesn't delete anything if the module was wrong.
     *
     */
    public function testDeletePermissionOfModuleNotExisting()
    {
        $this->out->deletePermissionOfModule('xyzmodule');
        self::assertCount(2, $this->out->getPermissions());
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        return $config->getInstallSql();
    }
}
