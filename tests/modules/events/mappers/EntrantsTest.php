<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use Modules\Comment\Config\Config as CommentModuleConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Events\Config\Config as ModuleConfig;
use Modules\Events\Mappers\Entrants as EntrantsMapper;
use Modules\Events\Models\Entrants as EntrantsModel;

class EntrantsTest extends DatabaseTestCase
{
    /**
     * @var EntrantsMapper
     */
    protected EntrantsMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new EntrantsMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all entries.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(EntrantsModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns entries ordered by event_id ASC (default).
     */
    public function testGetEntriesByOrderDefault()
    {
        $entries = $this->out->getEntriesBy();

        self::assertEquals(1, $entries[0]->getEventId());
        self::assertEquals(1, $entries[1]->getEventId());
        self::assertEquals(2, $entries[2]->getEventId());
    }

    /**
     * Tests that getEntriesBy() with explicit order by user_id ASC.
     */
    public function testGetEntriesByOrderUserId()
    {
        $entries = $this->out->getEntriesBy([], ['user_id' => 'ASC']);

        self::assertEquals(1, $entries[0]->getUserId());
        self::assertEquals(1, $entries[1]->getUserId());
        self::assertEquals(2, $entries[2]->getUserId());
    }

    /**
     * Tests that getEntriesBy() populates model fields correctly.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy(['event_id' => 1, 'user_id' => 1], []);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getEventId());
        self::assertEquals(1, $entries[0]->getUserId());
        self::assertEquals(1, $entries[0]->getStatus());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters by user_id.
     */
    public function testGetEntriesByWithWhereUserId()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 2], []);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getEventId());
        self::assertEquals(2, $entries[0]->getUserId());
        self::assertEquals(2, $entries[0]->getStatus());
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByNoResults()
    {
        $entries = $this->out->getEntriesBy(['event_id' => 9999], []);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() returns null when the table is empty.
     */
    public function testGetEntriesByEmptyTable()
    {
        $this->db->delete('events_entrants')->execute();

        $entries = $this->out->getEntriesBy();

        self::assertNull($entries);
    }

    /**
     * Tests that getEventEntrants() returns the correct entrant for event+user.
     */
    public function testGetEventEntrants()
    {
        $entrant = $this->out->getEventEntrants(1, 1);

        self::assertNotNull($entrant);
        self::assertEquals(1, $entrant->getEventId());
        self::assertEquals(1, $entrant->getUserId());
        self::assertEquals(1, $entrant->getStatus());
    }

    /**
     * Tests that getEventEntrants() returns the entrant with status 2 (maybe).
     */
    public function testGetEventEntrantsStatusMaybe()
    {
        $entrant = $this->out->getEventEntrants(1, 2);

        self::assertNotNull($entrant);
        self::assertEquals(1, $entrant->getEventId());
        self::assertEquals(2, $entrant->getUserId());
        self::assertEquals(2, $entrant->getStatus());
    }

    /**
     * Tests that getEventEntrants() returns null for a non-existent combination.
     */
    public function testGetEventEntrantsNotFound()
    {
        $entrant = $this->out->getEventEntrants(1, 9999);

        self::assertNull($entrant);
    }

    /**
     * Tests that getEventEntrants() returns null when table is empty.
     */
    public function testGetEventEntrantsEmptyTable()
    {
        $this->db->delete('events_entrants')->execute();

        $entrant = $this->out->getEventEntrants(1, 1);

        self::assertNull($entrant);
    }

    /**
     * Tests that getCountOfEventEntrans() returns the correct count.
     */
    public function testGetCountOfEventEntrants()
    {
        $count = $this->out->getCountOfEventEntrants(1);

        self::assertEquals(2, $count);
    }

    /**
     * Tests that getCountOfEventEntrans() returns 1 for an event with one entrant.
     */
    public function testGetCountOfEventEntrantsSingle()
    {
        $count = $this->out->getCountOfEventEntrants(2);

        self::assertEquals(1, $count);
    }

    /**
     * Tests that getCountOfEventEntrans() returns 0 when no entrants exist.
     */
    public function testGetCountOfEventEntrantsZero()
    {
        $count = $this->out->getCountOfEventEntrants(9999);

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getEventEntrantsById() returns all entrants for an event.
     */
    public function testGetEventEntrantsById()
    {
        $entrants = $this->out->getEventEntrantsById(1);

        self::assertIsArray($entrants);
        self::assertCount(2, $entrants);
        self::assertInstanceOf(EntrantsModel::class, $entrants[0]);
    }

    /**
     * Tests that getEventEntrantsById() returns entrants with correct fields.
     */
    public function testGetEventEntrantsByIdFields()
    {
        $entrants = $this->out->getEventEntrantsById(1);

        self::assertEquals(1, $entrants[0]->getEventId());
        self::assertEquals(1, $entrants[0]->getUserId());
        self::assertEquals(1, $entrants[0]->getStatus());

        self::assertEquals(1, $entrants[1]->getEventId());
        self::assertEquals(2, $entrants[1]->getUserId());
        self::assertEquals(2, $entrants[1]->getStatus());
    }

    /**
     * Tests that getEventEntrantsById() returns empty array for no entrants.
     */
    public function testGetEventEntrantsByIdNoResults()
    {
        $entrants = $this->out->getEventEntrantsById(9999);

        self::assertIsArray($entrants);
        self::assertCount(0, $entrants);
    }

    /**
     * Tests that getEventEntrantsById() returns empty array when table is empty.
     */
    public function testGetEventEntrantsByIdEmptyTable()
    {
        $this->db->delete('events_entrants')->execute();

        $entrants = $this->out->getEventEntrantsById(1);

        self::assertIsArray($entrants);
        self::assertCount(0, $entrants);
    }

    /**
     * Tests that saveUserOnEvent() inserts a new entrant when user is not yet registered.
     */
    public function testSaveUserOnEventInsert()
    {
        $model = new EntrantsModel();
        $model->setEventId(1);
        $model->setUserId(3);
        $model->setStatus(1);

        $this->out->saveUserOnEvent($model);

        $entrant = $this->out->getEventEntrants(1, 3);
        self::assertNotNull($entrant);
        self::assertEquals(1, $entrant->getStatus());
    }

    /**
     * Tests that saveUserOnEvent() updates an existing entrant when user is already registered.
     */
    public function testSaveUserOnEventUpdate()
    {
        // User 1 is already registered for event 1 with status 1.
        $model = new EntrantsModel();
        $model->setEventId(1);
        $model->setUserId(1);
        $model->setStatus(2);

        $this->out->saveUserOnEvent($model);

        $entrant = $this->out->getEventEntrants(1, 1);
        self::assertNotNull($entrant);
        self::assertEquals(2, $entrant->getStatus());
    }

    /**
     * Tests that saveUserOnEvent() update does not affect other entrants.
     */
    public function testSaveUserOnEventUpdatePreservesOthers()
    {
        $model = new EntrantsModel();
        $model->setEventId(1);
        $model->setUserId(1);
        $model->setStatus(2);

        $this->out->saveUserOnEvent($model);

        $entrant2 = $this->out->getEventEntrants(1, 2);
        self::assertNotNull($entrant2);
        self::assertEquals(2, $entrant2->getStatus());
    }

    /**
     * Tests that saveUserOnEvent() inserts for a new event+user pair.
     */
    public function testSaveUserOnEventNewEvent()
    {
        $model = new EntrantsModel();
        $model->setEventId(3);
        $model->setUserId(1);
        $model->setStatus(1);

        $this->out->saveUserOnEvent($model);

        $entrant = $this->out->getEventEntrants(3, 1);
        self::assertNotNull($entrant);
        self::assertEquals(1, $entrant->getStatus());
    }

    /**
     * Tests that deleteUserFromEvent() removes an entrant.
     */
    public function testDeleteUserFromEvent()
    {
        $this->out->deleteUserFromEvent(1, 1);

        self::assertNull($this->out->getEventEntrants(1, 1));

        $remaining = $this->out->getEventEntrantsById(1);
        self::assertCount(1, $remaining);
    }

    /**
     * Tests that deleteUserFromEvent() does not affect other users on the same event.
     */
    public function testDeleteUserFromEventPreservesOthers()
    {
        $this->out->deleteUserFromEvent(1, 1);

        $entrant2 = $this->out->getEventEntrants(1, 2);
        self::assertNotNull($entrant2);
        self::assertEquals(2, $entrant2->getUserId());
        self::assertEquals(2, $entrant2->getStatus());
    }

    /**
     * Tests that deleteUserFromEvent() for a non-existent combination does not throw.
     */
    public function testDeleteUserFromEventNotFound()
    {
        $this->out->deleteUserFromEvent(1, 9999);

        $entrants = $this->out->getEventEntrantsById(1);
        self::assertCount(2, $entrants);
    }

    /**
     * Tests that deleteUserFromEvent() removes the last remaining entrant.
     */
    public function testDeleteLastEntrant()
    {
        $this->out->deleteUserFromEvent(1, 1);
        $this->out->deleteUserFromEvent(1, 2);
        $this->out->deleteUserFromEvent(2, 1);

        $entrants = $this->out->getEventEntrantsById(1);
        self::assertCount(0, $entrants);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $commentModuleConfig = new CommentModuleConfig();

        return $commentModuleConfig->getInstallSql() . $config->getInstallSql();
    }
}
