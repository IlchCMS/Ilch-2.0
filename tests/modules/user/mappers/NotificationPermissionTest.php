<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Models\NotificationPermission as NotificationPermissionModel;
use PHPUnit\Ilch\PhpunitDataset;
use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Mappers\NotificationPermission as NotificationPermissionMapper;

/**
 * Tests the notification permission mapper class.
 *
 * @package ilch_phpunit
 */
class NotificationPermissionTest extends DatabaseTestCase
{
    protected $phpunitDataset;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/database_users_notifications.yml');
    }

    /**
     * Tests getting permission with a specific id.
     */
    public function testGetPermissionById()
    {
        $mapper = new NotificationPermissionMapper();
        $permission = $mapper->getPermissionById(1);

        self::assertNotNull($permission);
        self::assertEquals(1, $permission->getId());
        self::assertEquals(1, $permission->getUserId());
        self::assertEquals('forum', $permission->getModule());
        self::assertEquals('forumTest', $permission->getType());
        self::assertEquals(1, $permission->getGranted());
    }

    /**
     * Tests getting all permissions.
     */
    public function testGetPermissions()
    {
        $mapper = new NotificationPermissionMapper();
        $permissions = $mapper->getPermissions();

        self::assertNotNull($permissions);
        self::assertCount(3, $permissions);

        self::assertEquals(1, $permissions[0]->getId());
        self::assertEquals(1, $permissions[0]->getUserId());
        self::assertEquals('forum', $permissions[0]->getModule());
        self::assertEquals('forumTest', $permissions[0]->getType());
        self::assertEquals(1, $permissions[0]->getGranted());

        self::assertEquals(2, $permissions[1]->getId());
        self::assertEquals(1, $permissions[1]->getUserId());
        self::assertEquals('forum', $permissions[1]->getModule());
        self::assertEquals('forumTest2', $permissions[1]->getType());
        self::assertEquals(0, $permissions[1]->getGranted());
    }

    /**
     * Tests getting all not granted permissions.
     */
    public function testGetPermissionsNotGranted()
    {
        $mapper = new NotificationPermissionMapper();
        $permissions = $mapper->getPermissionsNotGranted();

        self::assertNotNull($permissions);
        self::assertCount(1, $permissions);

        self::assertEquals(2, $permissions[0]->getId());
        self::assertEquals(1, $permissions[0]->getUserId());
        self::assertEquals('forum', $permissions[0]->getModule());
        self::assertEquals('forumTest2', $permissions[0]->getType());
        self::assertEquals(0, $permissions[0]->getGranted());
    }

    /**
     * Tests getting all permissions of a module for a user.
     */
    public function testGetPermissionsOfModule()
    {
        $mapper = new NotificationPermissionMapper();
        $permissions = $mapper->getPermissionsOfModule('forum', 1);

        self::assertNotNull($permissions);
        self::assertCount(2, $permissions);

        self::assertEquals(1, $permissions[0]->getId());
        self::assertEquals(1, $permissions[0]->getUserId());
        self::assertEquals('forum', $permissions[0]->getModule());
        self::assertEquals('forumTest', $permissions[0]->getType());
        self::assertEquals(1, $permissions[0]->getGranted());

        self::assertEquals(2, $permissions[1]->getId());
        self::assertEquals(1, $permissions[1]->getUserId());
        self::assertEquals('forum', $permissions[1]->getModule());
        self::assertEquals('forumTest2', $permissions[1]->getType());
        self::assertEquals(0, $permissions[1]->getGranted());
    }

    /**
     * Test trying to get permissions for a non-existing module.
     */
    public function testGetPermissionsOfModuleNoResult()
    {
        $mapper = new NotificationPermissionMapper();
        $permissions = $mapper->getPermissionsOfModule('NotExistingModule', 1);

        self::assertSame([], $permissions);
    }

    /**
     * Tests updating permissions of a module for a user.
     */
    public function testUpdatePermissionGrantedOfModule()
    {
        $mapper = new NotificationPermissionMapper();
        $affectedRows = $mapper->updatePermissionGrantedOfModule('forum', 2, 0);

        self::assertEquals(1, $affectedRows);
    }

    /**
     * Tests updating permissions of a module with a specific type and for a specific user.
     */
    public function testUpdatePermissionGrantedOfModuleType()
    {
        $mapper = new NotificationPermissionMapper();
        $affectedRows = $mapper->updatePermissionGrantedOfModuleType('forum', 'forumTest', 1, 0);

        self::assertEquals(1, $affectedRows);
    }

    /**
     * Tests updating the value of granted for a specific permission.
     */
    public function testUpdatePermissionGrantedOfModuleById()
    {
        $mapper = new NotificationPermissionMapper();
        $affectedRows = $mapper->updatePermissionGrantedById([3], 2, 0);

        self::assertEquals(1, $affectedRows);
    }

    /**
     * Tests adding a permission for a module.
     */
    public function testAddPermissionForModule()
    {
        $mapper = new NotificationPermissionMapper();
        $permissionModel = new NotificationPermissionModel();
        $permissionModel->setModule('forum');
        $permissionModel->setUserId(1);
        $permissionModel->setType('forumTest3');
        $permissionModel->setGranted(1);

        $lastInsertId = $mapper->addPermissionForModule($permissionModel);
        $permission = $mapper->getPermissionById($lastInsertId);

        self::assertNotNull($permission);
        self::assertEquals($lastInsertId, $permission->getId());
        self::assertEquals(1, $permission->getUserId());
        self::assertEquals('forum', $permission->getModule());
        self::assertEquals('forumTest3', $permission->getType());
        self::assertEquals(1, $permission->getGranted());
    }

    /**
     * Tests adding multiple permissions.
     */
    public function testAddPermissions()
    {
        $permissionModels = [];
        $mapper = new NotificationPermissionMapper();

        for($userId = 1; $userId <= 3; $userId++) {
            $permissionModel = new NotificationPermissionModel();
            $permissionModel->setUserId($userId);
            $permissionModel->setModule('forum');
            $permissionModel->setType('forumTest4');
            $permissionModels[] = $permissionModel;
        }

        $affectedRows = $mapper->addPermissions($permissionModels);
        $permissions = $mapper->getPermissions();

        self::assertEquals(3, $affectedRows);

        self::assertEquals(4, $permissions[3]->getId());
        self::assertEquals(1, $permissions[3]->getUserId());
        self::assertEquals('forum', $permissions[3]->getModule());
        self::assertEquals('forumTest4', $permissions[3]->getType());
        self::assertEquals(1, $permissions[3]->getGranted());

        self::assertEquals(5, $permissions[4]->getId());
        self::assertEquals(2, $permissions[4]->getUserId());
        self::assertEquals('forum', $permissions[4]->getModule());
        self::assertEquals('forumTest4', $permissions[4]->getType());
        self::assertEquals(1, $permissions[4]->getGranted());

        self::assertEquals(6, $permissions[5]->getId());
        self::assertEquals(3, $permissions[5]->getUserId());
        self::assertEquals('forum', $permissions[5]->getModule());
        self::assertEquals('forumTest4', $permissions[5]->getType());
        self::assertEquals(1, $permissions[5]->getGranted());
    }

    /**
     * Tests deleting of permissions for a module.
     */
    public function testDeletePermissionOfModule()
    {
        $mapper = new NotificationPermissionMapper();

        self::assertEquals(2, $mapper->deletePermissionOfModule('forum', 1));
    }

    /**
     * Tests deleting of permissions of a module with a specific type.
     */
    public function testDeletePermissionForType()
    {
        $mapper = new NotificationPermissionMapper();

        self::assertEquals(1, $mapper->deletePermissionForType('forum', 'forumTest', 1));
    }

    /**
     * Tests deleting permissions by their id.
     */
    public function testDeletePermissionsById()
    {
        $mapper = new NotificationPermissionMapper();

        // Three IDs, but only two permissions get deleted, because the third one has an userid of 2.
        self::assertEquals(2, $mapper->deletePermissionsById([1,2,3], 1));
    }

    /**
     * Tests deleting all other (excluding the one specified by the id) permissions of a module.
     */
    public function testDeleteOtherPermissionsOfModule()
    {
        $mapper = new NotificationPermissionMapper();

        // Deletes only one permission even if there are two other rows for that module,
        // because one row has a value of 0 for granted.
        self::assertEquals(1, $mapper->deleteOtherPermissionsOfModule(1));
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql().$config->getInstallSql();
    }
}
