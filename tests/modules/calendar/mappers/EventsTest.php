<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Calendar\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Calendar\Mappers\Events as EventsMapper;
use Modules\Calendar\Models\Events as EntriesModel;

/**
 * Tests the Events mapper class.
 *
 * @package ilch_phpunit
 */
class EventsTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Events $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new EventsMapper();
    }

    /**
     * Tests if getEntries() returns all articles from the database.
     */
    public function testgetEventsAllRows()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(6, $entries);
    }

    public function testgetEvents()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(6, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('calendar/events/index/', $entries[$i]->getUrl());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('war/wars/index/', $entries[$i]->getUrl());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertSame('training/trainings/index/', $entries[$i]->getUrl());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertSame('birthday/birthdays/index/', $entries[$i]->getUrl());

        $i++;
        self::assertEquals(5, $entries[$i]->getId());
        self::assertSame('events/events/index/', $entries[$i]->getUrl());

        $i++;
        self::assertEquals(6, $entries[$i]->getId());
        self::assertSame('away/aways/index/', $entries[$i]->getUrl());
    }

    public function testsaveNewEvents()
    {
        $model = new EntriesModel();
        $model->setUrl('testurl');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('testurl', $entry->getUrl());
    }

    public function testsaveUpdateExistingEvents()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUrl('testurl');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('testurl', $entry->getUrl());
    }

    public function testdeleteEvents()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntryById(1);
        self::assertNull($entry);
    }

    /**
     * Tests that checkDB() returns true when the calendar_events table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getEntryById() returns the entry with the given id.
     */
    public function testGetEntryById()
    {
        $entry = $this->mapper->getEntryById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertSame('calendar/events/index/', $entry->getUrl());
    }

    /**
     * Tests that getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        self::assertNull($this->mapper->getEntryById(9999));
    }

    /**
     * Tests that getEntries() filters by the url field.
     */
    public function testGetEntriesByWhere()
    {
        $entries = $this->mapper->getEntries(['url' => 'war/wars/index/']);

        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertSame('war/wars/index/', $entries[0]->getUrl());
    }

    /**
     * Tests that getEntriesBy() respects the given order.
     */
    public function testGetEntriesByOrder()
    {
        $asc = $this->mapper->getEntriesBy([], ['id' => 'ASC']);
        self::assertEquals(1, $asc[0]->getId());
        self::assertEquals(6, $asc[5]->getId());

        $desc = $this->mapper->getEntriesBy([], ['id' => 'DESC']);
        self::assertEquals(6, $desc[0]->getId());
        self::assertEquals(1, $desc[5]->getId());
    }

    /**
     * Tests that getEntries() returns null when no events exist.
     */
    public function testGetEntriesEmptyReturnsNull()
    {
        foreach ([1, 2, 3, 4, 5, 6] as $id) {
            $this->mapper->delete($id);
        }

        self::assertNull($this->mapper->getEntries());
    }

    /**
     * Tests that updating an entry does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUrl('testurl');

        $this->mapper->save($model);

        $other = $this->mapper->getEntryById(2);
        self::assertNotNull($other);
        self::assertSame('war/wars/index/', $other->getUrl());
    }

    /**
     * Tests that delete() also accepts an Events model.
     */
    public function testDeleteAcceptsModel()
    {
        $model = $this->mapper->getEntryById(1);

        self::assertTrue($this->mapper->delete($model));
        self::assertNull($this->mapper->getEntryById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not affect existing entries.
     */
    public function testDeleteNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(6, $this->mapper->getEntries());
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
