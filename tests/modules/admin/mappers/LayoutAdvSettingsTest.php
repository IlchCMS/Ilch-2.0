<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;
use PHPUnit\Ilch\PhpunitDataset;

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
    protected LayoutAdvSettings $out;
    protected PhpunitDataset $phpunitDataset;

    /**
     * Filling the config object with individual testcase data.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new LayoutAdvSettings();
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

    /**
     * Test if getSetting() returns the expected setting.
     */
    public function testGetSetting()
    {
        $layoutSetting = $this->out->getSetting('testLayoutKey1', 'testKey1');

        self::assertEquals(1, $layoutSetting->getId());
        self::assertSame('testLayoutKey1', $layoutSetting->getLayoutKey());
        self::assertSame('testKey1', $layoutSetting->getKey());
        self::assertSame('testValue1', $layoutSetting->getValue());
    }

    /**
     * Test if getSettings() returns the expected settings.
     */
    public function testGetSettings()
    {
        $layoutSetting = $this->out->getSettings('testLayoutKey1');

        self::assertEquals(1, $layoutSetting['testKey1']->getId());
        self::assertSame('testLayoutKey1', $layoutSetting['testKey1']->getLayoutKey());
        self::assertSame('testKey1', $layoutSetting['testKey1']->getKey());
        self::assertSame('testValue1', $layoutSetting['testKey1']->getValue());

        self::assertEquals(2, $layoutSetting['testKey2']->getId());
        self::assertSame('testLayoutKey1', $layoutSetting['testKey2']->getLayoutKey());
        self::assertSame('testKey2', $layoutSetting['testKey2']->getKey());
        self::assertSame('testValue2', $layoutSetting['testKey2']->getValue());
    }

    /**
     * Test if getListOfLayoutKeys() returns the expected layout keys.
     */
    public function testGetListOfLayoutKeys()
    {
        $layoutKeyList = $this->out->getListOfLayoutKeys();

        self::assertCount(2, $layoutKeyList);
        self::assertSame('testLayoutKey1', $layoutKeyList[0]);
        self::assertSame('testLayoutKey2', $layoutKeyList[1]);
    }

    /**
     * Test if hasSettings() returns true for existing settings.
     */
    public function testHasSettings()
    {
        self::assertTrue($this->out->hasSettings('testLayoutKey1'));
    }

    /**
     * Test if hasSettings() returns false for non-existing settings.
     */
    public function testHasSettingsNotExisting()
    {
        self::assertFalse($this->out->hasSettings('notExistingLayoutKey'));
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

        $layoutSetting = $this->out->getSetting('testLayoutKey3', 'testKey5');
        self::assertEquals(5, $layoutSetting->getId());
        self::assertSame('testLayoutKey3', $layoutSetting->getLayoutKey());
        self::assertSame('testKey5', $layoutSetting->getKey());
        self::assertSame('testValue5', $layoutSetting->getValue());
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
        self::assertEquals(5, $layoutSetting['testKey5']->getId());
        self::assertSame('testLayoutKey3', $layoutSetting['testKey5']->getLayoutKey());
        self::assertSame('testKey5', $layoutSetting['testKey5']->getKey());
        self::assertSame('testValue5', $layoutSetting['testKey5']->getValue());

        self::assertEquals(6, $layoutSetting['testKey6']->getId());
        self::assertSame('testLayoutKey3', $layoutSetting['testKey6']->getLayoutKey());
        self::assertSame('testKey6', $layoutSetting['testKey6']->getKey());
        self::assertSame('testValue6', $layoutSetting['testKey6']->getValue());
    }

    /**
     * Test if deleteSetting() successfully deletes a specific setting.
     */
    public function testDeleteSetting()
    {
        $this->out->deleteSetting('testLayoutKey1', 'testKey1');
        self::assertEmpty($this->out->getSetting('testLayoutKey1', 'testKey1'));
    }

    /**
     * Test if deleteSettingById() successfully deletes the setting with a specific id.
     */
    public function testDeleteSettingById()
    {
        $this->out->deleteSettingById(2);
        self::assertEmpty($this->out->getSetting('testLayoutKey1', 'testKey2'));
    }

    /**
     * Test if deleteSettings() successfully deletes all settings with a specific layoutKey.
     */
    public function testDeleteSettings()
    {
        $this->out->deleteSettings('testLayoutKey2');
        self::assertEmpty($this->out->getSettings('testLayoutKey2'));
    }
}
