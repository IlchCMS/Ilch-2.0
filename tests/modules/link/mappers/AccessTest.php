<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Link\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Link\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Link\Mappers\Access as AccessMapper;

class AccessTest extends DatabaseTestCase
{
    /**
     * @var AccessMapper
     */
    protected Access $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new AccessMapper();
    }

    /**
     * Tests that save() (category access) inserts new rows for a category.
     */
    public function testSaveCategoryAccessInsert()
    {
        // Category 2 has no existing access rows
        $this->out->save(2, '1,2,3');

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(3, $rows);

        $groupIds = array_map(function ($row) {
            return (int) $row['group_id'];
        }, $rows);
        sort($groupIds);
        self::assertEquals([1, 2, 3], $groupIds);
    }

    /**
     * Tests that save() (category access) clears old rows and inserts new ones.
     */
    public function testSaveCategoryAccessUpdate()
    {
        // Category 1 has existing access: group_id 1 and 2
        $this->out->save(1, '3');

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(3, (int) $rows[0]['group_id']);
    }

    /**
     * Tests that save() (category access) with null does not modify existing rows.
     */
    public function testSaveCategoryAccessNull()
    {
        // Category 1 has existing access: group_id 1 and 2
        $this->out->save(1, null);

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(2, $rows);

        $groupIds = array_map(function ($row) {
            return (int) $row['group_id'];
        }, $rows);
        sort($groupIds);
        self::assertEquals([1, 2], $groupIds);
    }

    /**
     * Tests that save() (category access) with empty string clears all rows.
     */
    public function testSaveCategoryAccessEmptyString()
    {
        // Category 1 has existing access: group_id 1 and 2
        $this->out->save(1, '');

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(0, $rows);
    }

    /**
     * Tests that save() (category access) does not affect other categories.
     */
    public function testSaveCategoryAccessDoesNotAffectOthers()
    {
        // Update category 1 access
        $this->out->save(1, '3');

        // Category 3 should still have its original access (group_id 1)
        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 3])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(1, (int) $rows[0]['group_id']);
    }

    /**
     * Tests that saveLinksAccess() (link access) inserts new rows for a link.
     */
    public function testSaveLinksAccessInsert()
    {
        // Link 3 has no existing access rows
        $this->out->saveLinksAccess(3, '1,2');

        $rows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 3])
            ->execute()
            ->fetchRows();

        self::assertCount(2, $rows);

        $groupIds = array_map(function ($row) {
            return (int) $row['group_id'];
        }, $rows);
        sort($groupIds);
        self::assertEquals([1, 2], $groupIds);
    }

    /**
     * Tests that saveLinksAccess() (link access) clears old rows and inserts new ones.
     */
    public function testSaveLinksAccessUpdate()
    {
        // Link 2 has existing access: group_id 1 and 2
        $this->out->saveLinksAccess(2, '3');

        $rows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(3, (int) $rows[0]['group_id']);
    }

    /**
     * Tests that saveLinksAccess() with null does not modify existing rows.
     */
    public function testSaveLinksAccessNull()
    {
        // Link 1 has existing access: group_id 1
        $this->out->saveLinksAccess(1, null);

        $rows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(1, (int) $rows[0]['group_id']);
    }

    /**
     * Tests that saveLinksAccess() with empty string clears all rows.
     */
    public function testSaveLinksAccessEmptyString()
    {
        // Link 2 has existing access: group_id 1 and 2
        $this->out->saveLinksAccess(2, '');

        $rows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(0, $rows);
    }

    /**
     * Tests that saveLinksAccess() does not affect other links.
     */
    public function testSaveLinksAccessDoesNotAffectOthers()
    {
        // Update link 2 access
        $this->out->saveLinksAccess(2, '3');

        // Link 1 should still have its original access (group_id 1)
        $rows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 1])
            ->execute()
            ->fetchRows();

        self::assertCount(1, $rows);
        self::assertEquals(1, (int) $rows[0]['group_id']);
    }

    /**
     * Tests that category access and link access are stored in separate tables.
     */
    public function testCategoryAndLinkAccessAreSeparate()
    {
        // Set category 1 access
        $this->out->save(1, '1,2');

        // Set link 1 access
        $this->out->saveLinksAccess(1, '3');

        // Category 1 should have groups 1,2 in link_access
        $catRows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 1])
            ->execute()
            ->fetchRows();
        self::assertCount(2, $catRows);

        // Link 1 should have group 3 in link_links_access
        $linkRows = $this->db->select()
            ->from($this->out->accessLinksTable)
            ->where(['link_id' => 1])
            ->execute()
            ->fetchRows();
        self::assertCount(1, $linkRows);
        self::assertEquals(3, (int) $linkRows[0]['group_id']);
    }

    /**
     * Tests that save() with empty string on a category with no existing rows results in no rows.
     */
    public function testSaveCategoryAccessEmptyStringNoExisting()
    {
        // Category 2 has no existing access
        $this->out->save(2, '');

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(0, $rows);
    }

    /**
     * Tests that saving with whitespace-padded group ids is handled correctly.
     */
    public function testSaveWithWhitespaceGroupIds()
    {
        // " 1 , 2 " should still insert groups 1 and 2
        $this->out->save(2, ' 1 , 2 ');

        $rows = $this->db->select()
            ->from($this->out->accessTable)
            ->where(['cat_id' => 2])
            ->execute()
            ->fetchRows();

        self::assertCount(2, $rows);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
