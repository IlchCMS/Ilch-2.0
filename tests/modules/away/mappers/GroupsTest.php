<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Away\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Away\Config\Config as ModuleConfig;
use Modules\Away\Mappers\Groups as GroupMapper;
use Modules\User\Config\Config as UserConfig;

class GroupsTest extends DatabaseTestCase
{
    /**
     * @var GroupMapper
     */
    protected Groups $out;
    protected static $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
        $this->out = new GroupMapper();
    }

    /**
     * Tests if getGroups() returns all groups.
     */
    public function testGetGroups()
    {
        self::assertCount(2, $this->out->getGroups());
    }

    /**
     * Tests if getGroups() returns the correct group ids.
     */
    public function testGetGroupsValues()
    {
        $groups = $this->out->getGroups();

        self::assertIsArray($groups);
        self::assertEquals([1, 2], $groups);
    }

    /**
     * Tests if the groups get saved.
     */
    public function testSave()
    {
        $affectedRows = $this->out->save([1,2,3]);
        $groups = $this->out->getGroups();

        self::assertEquals(3, $affectedRows);
        self::assertEquals([1,2,3], $groups);
        self::assertCount(3, $groups);
    }

    /**
     * Tests if save() replaces the existing groups (truncate + insert).
     */
    public function testSaveReplacesExistingGroups()
    {
        $affectedRows = $this->out->save([3]);
        $groups = $this->out->getGroups();

        self::assertSame(1, $affectedRows);
        self::assertEquals([3], $groups);
        self::assertCount(1, $groups);
    }

    /**
     * Tests if save() with an empty array removes all groups.
     */
    public function testSaveEmpty()
    {
        $affectedRows = $this->out->save([]);
        $groups = $this->out->getGroups();

        self::assertSame(0, $affectedRows);
        self::assertIsArray($groups);
        self::assertEmpty($groups);
    }

    /**
     * Tests if save() chunks more than 25 groups.
     */
    public function testSaveMoreThanChunkSize()
    {
        $groups = range(1, 30);

        $affectedRows = $this->out->save($groups);
        $result = $this->out->getGroups();

        self::assertSame(30, $affectedRows);
        self::assertCount(30, $result);
        self::assertEquals($groups, $result);
    }

    /**
     * Tests if the new group gets added properly.
     */
    public function testAddGroups()
    {
        $affectedRows = $this->out->addGroups([3]);
        $groups = $this->out->getGroups();

        self::assertEquals(1, $affectedRows);
        self::assertEquals([1,2,3], $groups);
        self::assertCount(3, $groups);
    }

    /**
     * Tests if addGroups() skips groups that already exist.
     */
    public function testAddGroupsSkipsExisting()
    {
        $affectedRows = $this->out->addGroups([1, 3]);
        $groups = $this->out->getGroups();

        self::assertSame(1, $affectedRows);
        self::assertEquals([1, 2, 3], $groups);
        self::assertCount(3, $groups);
    }

    /**
     * Tests if addGroups() with only existing groups changes nothing.
     */
    public function testAddGroupsAllExisting()
    {
        $affectedRows = $this->out->addGroups([1, 2]);
        $groups = $this->out->getGroups();

        self::assertSame(0, $affectedRows);
        self::assertEquals([1, 2], $groups);
        self::assertCount(2, $groups);
    }

    /**
     * Tests if addGroups() with an empty array changes nothing.
     */
    public function testAddGroupsEmpty()
    {
        $affectedRows = $this->out->addGroups([]);
        $groups = $this->out->getGroups();

        self::assertSame(0, $affectedRows);
        self::assertEquals([1, 2], $groups);
        self::assertCount(2, $groups);
    }

    /**
     * Tests if addGroups() on an empty table adds all groups.
     */
    public function testAddGroupsOnEmptyTable()
    {
        $this->out->save([]);

        $affectedRows = $this->out->addGroups([1, 2, 3]);
        $groups = $this->out->getGroups();

        self::assertSame(3, $affectedRows);
        self::assertEquals([1, 2, 3], $groups);
        self::assertCount(3, $groups);
    }

    /**
     * Tests if addGroups() chunks more than 25 new groups.
     */
    public function testAddGroupsMoreThanChunkSize()
    {
        $this->out->save([]);

        $groups = range(1, 30);
        $affectedRows = $this->out->addGroups($groups);
        $result = $this->out->getGroups();

        self::assertSame(30, $affectedRows);
        self::assertCount(30, $result);
        self::assertEquals($groups, $result);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        $schemaSql = $adminConfig->getInstallSql()
            . $userConfig->getInstallSql()
            . $config->getInstallSql();

        // The chunk-size tests need more than 25 groups. The Admin install SQL
        // seeds groups 1-3, so create 4-30 here to satisfy the foreign key
        // constraint on away_groups.group_id.
        $values = [];
        for ($i = 4; $i <= 30; $i++) {
            $values[] = sprintf('(%d, "Group %d")', $i, $i);
        }

        return $schemaSql
            . 'INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES '
            . implode(', ', $values)
            . ';';
    }
}
