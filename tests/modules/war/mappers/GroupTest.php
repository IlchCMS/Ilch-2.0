<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Media\Config\Config as MediaConfig;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Models\Group as EntriesModel;

/**
 * @package ilch_phpunit
 */
class GroupTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Group $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new GroupMapper();
    }

    /**
     * All test routines.
     */
    public function testgetWarGroupAllRows()
    {
        $entries = $this->mapper->getGroups();

        self::assertCount(2, $entries);
    }

    public function testgetWarGroup()
    {
        $entries = $this->mapper->getGroups();

        self::assertCount(2, $entries);

        $i = 0;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('Testgruppe2', $entries[$i]->getGroupName());
        self::assertSame('ILCH2-TG2', $entries[$i]->getGroupTag());
        self::assertSame('', $entries[$i]->getGroupImage());
        self::assertSame('1', $entries[$i]->getGroupMember());
        self::assertSame('', $entries[$i]->getGroupDesc());

        $i++;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('Testgruppe1', $entries[$i]->getGroupName());
        self::assertSame('ILCH2-TG1', $entries[$i]->getGroupTag());
        self::assertSame('', $entries[$i]->getGroupImage());
        self::assertSame('2', $entries[$i]->getGroupMember());
        self::assertSame('', $entries[$i]->getGroupDesc());
    }

    public function testsaveNewWarGroup()
    {
        $model = new EntriesModel();
        $model->setGroupName('Testgruppe3');
        $model->setGroupTag('ILCH2-TG3');
        $model->setGroupImage('');
        $model->setGroupMember('3');
        $model->setGroupDesc('');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getGroupById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('Testgruppe3', $entry->getGroupName());
        self::assertSame('ILCH2-TG3', $entry->getGroupTag());
        self::assertSame('', $entry->getGroupImage());
        self::assertSame('3', $entry->getGroupMember());
        self::assertSame('', $entry->getGroupDesc());
    }

    public function testsaveUpdateExistingWarGroup()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setGroupName('Testgruppe3');
        $model->setGroupTag('ILCH2-TG3');
        $model->setGroupImage('');
        $model->setGroupMember('3');
        $model->setGroupDesc('');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getGroupById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Testgruppe3', $entry->getGroupName());
        self::assertSame('ILCH2-TG3', $entry->getGroupTag());
        self::assertSame('', $entry->getGroupImage());
        self::assertSame('3', $entry->getGroupMember());
        self::assertSame('', $entry->getGroupDesc());
    }

    public function testdeleteWarGroup()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getGroupById(1);
        self::assertNull($entry);
    }

    /**
     * Tests that checkDB() returns true if the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getGroupById() returns null for a non-existent id.
     */
    public function testGetGroupByIdNotFound()
    {
        self::assertNull($this->mapper->getGroupById(9999));
    }

    /**
     * Tests that getGroupById() accepts a model instance.
     */
    public function testGetGroupByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        $entry = $this->mapper->getGroupById($model);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Testgruppe1', $entry->getGroupName());
    }

    /**
     * Tests that getGroups() filters with a where clause.
     */
    public function testGetGroupByWhere()
    {
        $entries = $this->mapper->getGroups(['g.tag' => 'ILCH2-TG1']);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertSame('Testgruppe1', $entries[0]->getGroupName());
    }

    /**
     * Tests that getGroupList() returns all groups.
     */
    public function testGetGroupList()
    {
        $entries = $this->mapper->getGroupList();

        self::assertCount(2, $entries);
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteWarGroupWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getGroupById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteWarGroupNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(2, $this->mapper->getGroups());
    }

    /**
     * Tests that getJson() returns a JSON encoded array of all groups.
     */
    public function testGetJson()
    {
        $json = $this->mapper->getJson();

        self::assertIsString($json);

        $entries = json_decode($json, true);
        self::assertIsArray($entries);
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getJson() returns an empty JSON array when no groups exist.
     */
    public function testGetJsonEmpty()
    {
        $this->mapper->delete(1);
        $this->mapper->delete(2);

        self::assertSame('[]', $this->mapper->getJson());
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();
        $configMedia = new MediaConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configMedia->getInstallSql() . $config->getInstallSql();
    }
}
