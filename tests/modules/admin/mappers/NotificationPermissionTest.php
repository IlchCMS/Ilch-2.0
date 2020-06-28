<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;

/**
 * Tests the NotificationPermission mapper class.
 *
 * @package ilch_phpunit
 */
class NotificationPermissionTest extends DatabaseTestCase
{
    /**
     * @var UserMapper
     */
    protected $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new NotificationPermissionMapper();
    }

    /**
     * Tests if getPermissions() returns all permissions from the database.
     *
     */
    public function testGetPermissions()
    {
        $this->assertTrue(count($this->out->getPermissions()) == 2);
    }

    /**
     * Tests if getPermissionOfModule() returns null if there is no permission for a module.
     *
     */
    public function testGetPermissionOfModuleNotExisting()
    {
        $this->assertNull($this->out->getPermissionOfModule('xyzmodul'));
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
        $this->assertEquals($notificationPermissionModel, $this->out->getPermissionOfModule('article'));
    }

    /**
     * Tests if updatePermissionGrantedOfModule() successfully updates the granted value of the NotificationPermissionModel.
     *
     */
    public function testUpdatePermissionGrantedOfModule()
    {
        $this->out->updatePermissionGrantedOfModule('article', false);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        $this->assertTrue($notificationPermissionModel->getGranted() == 0);
    }

    /**
     * Tests if updateLimitOfModule() successfully updates the limit value of the NotificationPermissionModel.
     *
     */
    public function testUpdateLimitOfModule()
    {
        $this->out->updateLimitOfModule('article', 3);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        $this->assertTrue($notificationPermissionModel->getLimit() == 3);
    }

    /**
     * Tests if updateLimitOfModule() successfully returns the maximum value of limit (UNSIGNED TINYINT) if one
     * tries to set it to an bigger value.
     *
     */
    public function testUpdateLimitOfModuleInvalidLimit()
    {
        $this->out->updateLimitOfModule('article', 300);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        // If the limit is bigger than 255 (UNSIGNED TINYINT) then it gets set to the maximum of 255.
        $this->assertTrue($notificationPermissionModel->getLimit() == 255);
    }

    /**
     * Tests if updateLimitOfModule() successfully returns the minimum value of limit (UNSIGNED TINYINT) if one
     * tries to set it to an negative value.
     *
     */
    public function testUpdateLimitOfModuleInvalidLimitNegative()
    {
        $this->out->updateLimitOfModule('article', -1);
        $notificationPermissionModel = $this->out->getPermissionOfModule('article');
        // If the limit is negative (UNSIGNED) then it gets set to 0.
        $this->assertTrue($notificationPermissionModel->getLimit() == 0);
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
        $this->assertEquals($notificationPermissionModel, $this->out->getPermissionOfModule('away'));
    }

    /**
     * Tests if deletePermissionOfModule() successfully deletes a permission of a module.
     *
     */
    public function testDeletePermissionOfModule()
    {
        $this->out->deletePermissionOfModule('article');
        $this->assertNull($this->out->getPermissionOfModule('article'));
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        return $config->getInstallSql();
    }
}
