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
    protected $out;
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
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
     * Tests if the new group gets added properly.
     */
    public function addGroups()
    {
        $affectedRows = $this->out->addGroups([3]);
        $groups = $this->out->getGroups();

        self::assertEquals(3, $affectedRows);
        self::assertEquals([1,2,3], $groups);
        self::assertCount(3, $groups);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql().$userConfig->getInstallSql().$config->getInstallSql();
    }
}
