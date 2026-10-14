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
use Modules\War\Mappers\Maps as MapsMapper;
use Modules\War\Models\Maps as EntriesModel;

/**
 * @package ilch_phpunit
 */
class MapsTest extends DatabaseTestCase
{
    protected static $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Maps $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new MapsMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarMapsAllRows()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(2, $entries);
    }

    public function testgetWarMaps()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(2, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('Map1', $entries[$i]->getName());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('Map2', $entries[$i]->getName());
    }

    public function testsaveNewWarMaps()
    {
        $model = new EntriesModel();
        $model->setName('Map3');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('Map3', $entry->getName());
    }

    public function testsaveUpdateExistingWarMaps()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setName('Map3');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Map3', $entry->getName());
    }

    public function testdeleteWarMaps()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntryById(1);
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
     * Tests that getList() returns all maps ordered by id ASC.
     */
    public function testGetList()
    {
        $entries = $this->mapper->getList();

        self::assertCount(2, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests that getEntriesBy() filters with a where clause.
     */
    public function testGetEntriesByWhere()
    {
        $entries = $this->mapper->getEntriesBy(['id' => 2]);

        self::assertCount(1, $entries);
        self::assertSame('Map2', $entries[0]->getName());
    }

    /**
     * Tests that getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        self::assertNull($this->mapper->getEntryById(9999));
    }

    /**
     * Tests that getEntryById() accepts a model instance.
     */
    public function testGetEntryByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        $entry = $this->mapper->getEntryById($model);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Map1', $entry->getName());
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteWarMapsWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getEntryById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteWarMapsNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(2, $this->mapper->getEntries());
    }

    /**
     * Tests that getJson() returns a JSON encoded array of all maps.
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
     * Tests that getJson() returns an empty JSON array when no maps exist.
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

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
