<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\LayoutAdvSettings;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

/**
 * Tests the LayoutAdvSettings mapper class.
 *
 * @package ilch_phpunit
 */
class LayoutAdvSettingsTest extends DatabaseTestCase
{
    /**
     * @var LayoutAdvSettings
     */
    protected $out;

    public function setUp()
    {
        parent::setUp();
        $this->out = new LayoutAdvSettings();
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

    /**
     * Test if getSetting() returns the expected setting.
     */
    public function testGetSetting()
    {
        $layoutSetting = $this->out->getSetting('testLayoutKey1', 'testKey1');

        $this->assertEquals($layoutSetting->getId(), 1);
        $this->assertSame($layoutSetting->getLayoutKey(), 'testLayoutKey1');
        $this->assertSame($layoutSetting->getKey(), 'testKey1');
        $this->assertSame($layoutSetting->getValue(), 'testValue1');
    }

    /**
     * Test if getSettings() returns the expected settings.
     */
    public function testGetSettings()
    {
        $layoutSetting = $this->out->getSettings('testLayoutKey1');

        $this->assertEquals($layoutSetting['testKey1']->getId(), 1);
        $this->assertSame($layoutSetting['testKey1']->getLayoutKey(), 'testLayoutKey1');
        $this->assertSame($layoutSetting['testKey1']->getKey(), 'testKey1');
        $this->assertSame($layoutSetting['testKey1']->getValue(), 'testValue1');

        $this->assertEquals($layoutSetting['testKey2']->getId(), 2);
        $this->assertSame($layoutSetting['testKey2']->getLayoutKey(), 'testLayoutKey1');
        $this->assertSame($layoutSetting['testKey2']->getKey(), 'testKey2');
        $this->assertSame($layoutSetting['testKey2']->getValue(), 'testValue2');
    }

    /**
     * Test if getListOfLayoutKeys() returns the expected layout keys.
     */
    public function testGetListOfLayoutKeys()
    {
        $layoutKeyList = $this->out->getListOfLayoutKeys();

        $this->assertCount(2, $layoutKeyList);
        $this->assertSame($layoutKeyList[0], 'testLayoutKey1');
        $this->assertSame($layoutKeyList[1], 'testLayoutKey2');
    }

    /**
     * Test if hasSettings() returns true for existing settings.
     */
    public function testHasSettings()
    {
        $this->assertTrue($this->out->hasSettings('testLayoutKey1'));
    }

    /**
     * Test if hasSettings() returns false for non existing settings.
     */
    public function testHasSettingsNotExisting()
    {
        $this->assertFalse($this->out->hasSettings('notExistingLayoutKey'));
    }

    /**
     * Test if save() saves the setting correctly.
     */
    public function testSaveSingleOne()
    {
        $layoutSettingModel = new LayoutAdvSettingsModel();
        $layoutSettingModel->setLayoutKey('testLayoutKey3');
        $layoutSettingModel->setKey('testKey5');
        $layoutSettingModel->setValue('testValue5');
        $layoutSettingsArray[] = $layoutSettingModel;
        $this->out->save($layoutSettingsArray);

        $layoutSetting = $this->out->getSetting('testLayoutKey3','testKey5');
        $this->assertEquals($layoutSetting->getId(), 5);
        $this->assertSame($layoutSetting->getLayoutKey(), 'testLayoutKey3');
        $this->assertSame($layoutSetting->getKey(), 'testKey5');
        $this->assertSame($layoutSetting->getValue(), 'testValue5');
    }

    /**
     * Test if save() saves the settings correctly.
     */
    public function testSaveMultiple()
    {
        $layoutSettingModel = new LayoutAdvSettingsModel();
        $layoutSettingModel->setLayoutKey('testLayoutKey3');
        $layoutSettingModel->setKey('testKey5');
        $layoutSettingModel->setValue('testValue5');
        $layoutSettingsArray[] = $layoutSettingModel;

        $layoutSettingModel = new LayoutAdvSettingsModel();
        $layoutSettingModel->setLayoutKey('testLayoutKey3');
        $layoutSettingModel->setKey('testKey6');
        $layoutSettingModel->setValue('testValue6');
        $layoutSettingsArray[] = $layoutSettingModel;

        $this->out->save($layoutSettingsArray);

        $layoutSetting = $this->out->getSettings('testLayoutKey3');
        $this->assertEquals($layoutSetting['testKey5']->getId(), 5);
        $this->assertSame($layoutSetting['testKey5']->getLayoutKey(), 'testLayoutKey3');
        $this->assertSame($layoutSetting['testKey5']->getKey(), 'testKey5');
        $this->assertSame($layoutSetting['testKey5']->getValue(), 'testValue5');

        $this->assertEquals($layoutSetting['testKey6']->getId(), 6);
        $this->assertSame($layoutSetting['testKey6']->getLayoutKey(), 'testLayoutKey3');
        $this->assertSame($layoutSetting['testKey6']->getKey(), 'testKey6');
        $this->assertSame($layoutSetting['testKey6']->getValue(), 'testValue6');
    }

    /**
     * Test if deleteSetting() successfully deletes a specific setting.
     */
    public function testDeleteSetting()
    {
        $this->out->deleteSetting('testLayoutKey1','testKey1');
        $this->assertEmpty($this->out->getSetting('testLayoutKey1','testKey1'));
    }

    /**
     * Test if deleteSettingById() successfully deletes the setting with a specific id.
     */
    public function testDeleteSettingById()
    {
        $this->out->deleteSettingById(2);
        $this->assertEmpty($this->out->getSetting('testLayoutKey1','testKey2'));
    }

    /**
     * Test if deleteSettings() successfully deletes all settings with a specific layoutKey.
     */
    public function testDeleteSettings()
    {
        $this->out->deleteSettings('testLayoutKey2');
        $this->assertEmpty($this->out->getSettings('testLayoutKey2'));
    }
}
