<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Downloads\Tests;

use Modules\Media\Config\Config as MediaModuleConfig;
use Modules\User\Config\Config as UserModuleConfig;
use Modules\Admin\Config\Config as AdminModuleConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Downloads\Config\Config as ModuleConfig;
use Modules\Downloads\Mappers\Access as AccessMapper;

class AccessTest extends DatabaseTestCase
{
    /**
     * @var AccessMapper
     */
    protected AccessMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new AccessMapper();
    }

    /**
     * Tests that save() inserts access rows for an item.
     */
    public function testSaveItemAccess()
    {
        $this->out->save(1, '2,3');

        $rows = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 1])
            ->execute()
            ->fetchList();

        self::assertCount(2, $rows);
        self::assertContains('2', $rows);
        self::assertContains('3', $rows);
    }

    /**
     * Tests that save() replaces existing access when called again.
     */
    public function testSaveItemAccessOverwrites()
    {
        // Item 2 already has groups 1 and 2 in fixture.
        $this->out->save(2, '3');

        $rows = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 2])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('3', $rows[0]);
    }

    /**
     * Tests that save() with null access does nothing.
     */
    public function testSaveItemAccessNull()
    {
        // Item 3 already has group 1 in fixture.
        $this->out->save(3, null);

        $rows = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 3])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('1', $rows[0]);
    }

    /**
     * Tests that save() with empty string access clears all existing rows.
     */
    public function testSaveItemAccessEmptyString()
    {
        // Item 2 already has groups 1 and 2.
        $this->out->save(2, '');

        $rows = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 2])
            ->execute()
            ->fetchList();

        self::assertCount(0, $rows);
    }

    /**
     * Tests that save() does not affect other items' access.
     */
    public function testSaveItemAccessPreservesOthers()
    {
        $this->out->save(1, '3');

        $rows2 = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 2])
            ->execute()
            ->fetchList();

        self::assertCount(2, $rows2);
        self::assertContains('1', $rows2);
        self::assertContains('2', $rows2);
    }

    /**
     * Tests that saveFileAccess() inserts access rows for a file.
     */
    public function testSaveFileAccess()
    {
        $this->out->saveFileAccess(1, '2,3');

        $rows = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 1])
            ->execute()
            ->fetchList();

        self::assertCount(2, $rows);
        self::assertContains('2', $rows);
        self::assertContains('3', $rows);
    }

    /**
     * Tests that saveFileAccess() replaces existing file access when called again.
     */
    public function testSaveFileAccessOverwrites()
    {
        // File 2 already has group 2 in fixture.
        $this->out->saveFileAccess(2, '1');

        $rows = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 2])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('1', $rows[0]);
    }

    /**
     * Tests that saveFileAccess() with null does nothing.
     */
    public function testSaveFileAccessNull()
    {
        // File 3 already has group 1.
        $this->out->saveFileAccess(3, null);

        $rows = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 3])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('1', $rows[0]);
    }

    /**
     * Tests that saveFileAccess() with empty string clears existing file access.
     */
    public function testSaveFileAccessEmptyString()
    {
        // File 1 already has group 1.
        $this->out->saveFileAccess(1, '');

        $rows = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 1])
            ->execute()
            ->fetchList();

        self::assertCount(0, $rows);
    }

    /**
     * Tests that saveFileAccess() does not affect other files' access.
     */
    public function testSaveFileAccessPreservesOthers()
    {
        $this->out->saveFileAccess(1, '3');

        $rows3 = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 3])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows3);
        self::assertEquals('1', $rows3[0]);
    }

    /**
     * Tests that save() with a single group id works correctly.
     */
    public function testSaveSingleGroup()
    {
        $this->out->save(1, '1');

        $rows = $this->db->select('group_id')
            ->from('downloads_access')
            ->where(['item_id' => 1])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('1', $rows[0]);
    }

    /**
     * Tests that saveFileAccess() with a single group id works correctly.
     */
    public function testSaveFileAccessSingleGroup()
    {
        $this->out->saveFileAccess(1, '1');

        $rows = $this->db->select('group_id')
            ->from('downloads_files_access')
            ->where(['file_id' => 1])
            ->execute()
            ->fetchList();

        self::assertCount(1, $rows);
        self::assertEquals('1', $rows[0]);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userModuleConfig = new UserModuleConfig();
        $mediaModuleConfig = new MediaModuleConfig();
        $adminModuleConfig = new AdminModuleConfig();

        return $adminModuleConfig->getInstallSql() . $userModuleConfig->getInstallSql() . $mediaModuleConfig->getInstallSql() . $config->getInstallSql();
    }
}
