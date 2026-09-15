<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Events\Config\Config as ModuleConfig;
use Modules\Comment\Config\Config as CommentModuleConfig;
use Modules\Events\Mappers\Events as EventsMapper;
use Modules\Events\Models\Events as EventModel;

class EventsTest extends DatabaseTestCase
{
    /**
     * @var EventsMapper
     */
    protected EventsMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new EventsMapper();
    }

    /**
     * Tests that getEntriesBy() returns all events.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(5, $entries);
        self::assertInstanceOf(EventModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns events ordered by start ASC (default).
     */
    public function testGetEntriesByOrderDefault()
    {
        $entries = $this->out->getEntriesBy();

        self::assertEquals('2020-12-25 10:00:00', $entries[0]->getStart());
        self::assertEquals('2025-06-15 10:00:00', $entries[1]->getStart());
    }

    /**
     * Tests that getEntriesBy() with explicit DESC order.
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->out->getEntriesBy([], ['start' => 'DESC']);

        self::assertEquals('2027-01-15 14:00:00', $entries[0]->getStart());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters by user_id.
     */
    public function testGetEntriesByWithWhereUserId()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters by type.
     */
    public function testGetEntriesByWithWhereType()
    {
        $entries = $this->out->getEntriesBy(['type' => 'Social']);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters by show.
     */
    public function testGetEntriesByWithWhereShow()
    {
        $entries = $this->out->getEntriesBy(['show' => 1]);

        self::assertNotNull($entries);
        self::assertCount(4, $entries);
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByNoResults()
    {
        $entries = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() returns null when the table is empty.
     */
    public function testGetEntriesByEmptyTable()
    {
        $this->db->delete('events')->execute();

        $entries = $this->out->getEntriesBy();

        self::assertNull($entries);
    }

    /**
     * Tests that getEntries() delegates to getEntriesBy().
     */
    public function testGetEntries()
    {
        $entries = $this->out->getEntries();

        self::assertNotNull($entries);
        self::assertCount(5, $entries);
    }

    /**
     * Tests that getEntries() with WHERE clause filters correctly.
     */
    public function testGetEntriesWithWhere()
    {
        $entries = $this->out->getEntries(['id' => 1]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals('Tech Conference', $entries[0]->getTitle());
    }

    /**
     * Tests that getEventById() returns the correct event.
     */
    public function testGetEventById()
    {
        $event = $this->out->getEventById(1);

        self::assertNotNull($event);
        self::assertEquals(1, $event->getId());
        self::assertEquals('Tech Conference', $event->getTitle());
        self::assertEquals('Berlin', $event->getPlace());
        self::assertEquals('Conference', $event->getType());
        self::assertEquals(1, $event->getUserId());
        self::assertEquals(1, $event->getShow());
    }

    /**
     * Tests that getEventById() returns null for a non-existent id.
     */
    public function testGetEventByIdNotFound()
    {
        $event = $this->out->getEventById(9999);

        self::assertNull($event);
    }

    /**
     * Tests that getEventById() returns null when table is empty.
     */
    public function testGetEventByIdEmptyTable()
    {
        $this->db->delete('events')->execute();

        $event = $this->out->getEventById(1);

        self::assertNull($event);
    }

        /**
         * Tests that getEventListUpcoming() returns events with start > NOW().
         */
    public function testGetEventListUpcoming()
    {
        $future = (new \DateTime('+2 years'))->format('Y-m-d H:i:s');
        $past = (new \DateTime('-2 years'))->format('Y-m-d H:i:s');

        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 4])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 5])->execute();

        $events = $this->out->getEventListUpcoming();

        self::assertNotNull($events);
        self::assertCount(1, $events);
        self::assertEquals(3, $events[0]->getId());
        self::assertEquals('Networking Lunch', $events[0]->getTitle());
    }

        /**
         * Tests that getEventListUpcoming() with limit returns at most $limit events.
         */
    public function testGetEventListUpcomingWithLimit()
    {
        $future = (new \DateTime('+2 years'))->format('Y-m-d H:i:s');
        $past = (new \DateTime('-2 years'))->format('Y-m-d H:i:s');

        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 4])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $past])->where(['id' => 5])->execute();

        $events = $this->out->getEventListUpcoming(1);

        self::assertNotNull($events);
        self::assertCount(1, $events);
    }

    /**
     * Tests that getEventListUpcoming() returns null when no upcoming events exist.
     */
    public function testGetEventListUpcomingEmpty()
    {
        $this->db->update('events')
            ->values(['start' => '2020-01-01 00:00:00', 'end' => '2020-01-01 00:00:00'])
            ->execute();

        $events = $this->out->getEventListUpcoming();

        self::assertNull($events);
    }

        /**
         * Tests that getEventListPast() returns events with end < NOW().
         */
    public function testGetEventListPast()
    {
        $past1 = (new \DateTime('-1 year'))->format('Y-m-d H:i:s');
        $past2 = (new \DateTime('-5 years'))->format('Y-m-d H:i:s');
        $future = (new \DateTime('+2 years'))->format('Y-m-d H:i:s');

        $this->db->update('events')->values(['start' => $past1, 'end' => $past1])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $past2, 'end' => $past2])->where(['id' => 4])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 5])->execute();

        $events = $this->out->getEventListPast();

        self::assertNotNull($events);
        self::assertCount(2, $events);
        self::assertEquals(1, $events[0]->getId());
        self::assertEquals(4, $events[1]->getId());
    }

        /**
         * Tests that getEventListPast() with limit returns at most $limit events.
         */
    public function testGetEventListPastWithLimit()
    {
        $past1 = (new \DateTime('-1 year'))->format('Y-m-d H:i:s');
        $past2 = (new \DateTime('-5 years'))->format('Y-m-d H:i:s');
        $future = (new \DateTime('+2 years'))->format('Y-m-d H:i:s');

        $this->db->update('events')->values(['start' => $past1, 'end' => $past1])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $past2, 'end' => $past2])->where(['id' => 4])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 5])->execute();

        $events = $this->out->getEventListPast(1);

        self::assertNotNull($events);
        self::assertCount(1, $events);
    }

    /**
     * Tests that getEventListPast() returns null when no past events exist.
     */
    public function testGetEventListPastEmpty()
    {
        $this->db->update('events')
            ->values(['start' => '2030-01-01 00:00:00', 'end' => '2030-01-01 00:00:00'])
            ->execute();

        $events = $this->out->getEventListPast();

        self::assertNull($events);
    }

        /**
         * Tests that getEventListCurrent() returns events where start < NOW() AND end > NOW().
         */
    public function testGetEventListCurrent()
    {
        $past = (new \DateTime('-1 year'))->format('Y-m-d H:i:s');
        $future = (new \DateTime('+1 year'))->format('Y-m-d H:i:s');
        $farPast = (new \DateTime('-5 years'))->format('Y-m-d H:i:s');

        // Events 2 and 5 span the current time
        $this->db->update('events')->values(['start' => $past, 'end' => $future])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $future])->where(['id' => 5])->execute();
        // Events 1, 3, 4 are clearly not current
        $this->db->update('events')->values(['start' => $farPast, 'end' => $farPast])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $farPast, 'end' => $farPast])->where(['id' => 4])->execute();

        $events = $this->out->getEventListCurrent();

        self::assertNotNull($events);
        self::assertCount(2, $events);
    }

        /**
         * Tests that getEventListCurrent() with limit returns at most $limit events.
         */
    public function testGetEventListCurrentWithLimit()
    {
        $past = (new \DateTime('-1 year'))->format('Y-m-d H:i:s');
        $future = (new \DateTime('+1 year'))->format('Y-m-d H:i:s');
        $farPast = (new \DateTime('-5 years'))->format('Y-m-d H:i:s');

        $this->db->update('events')->values(['start' => $past, 'end' => $future])->where(['id' => 2])->execute();
        $this->db->update('events')->values(['start' => $past, 'end' => $future])->where(['id' => 5])->execute();
        $this->db->update('events')->values(['start' => $farPast, 'end' => $farPast])->where(['id' => 1])->execute();
        $this->db->update('events')->values(['start' => $future, 'end' => $future])->where(['id' => 3])->execute();
        $this->db->update('events')->values(['start' => $farPast, 'end' => $farPast])->where(['id' => 4])->execute();

        $events = $this->out->getEventListCurrent(1);

        self::assertNotNull($events);
        self::assertCount(1, $events);
    }

    /**
     * Tests that getEventListCurrent() returns null when no current events exist.
     */
    public function testGetEventListCurrentEmpty()
    {
        $this->db->update('events')
            ->values(['start' => '2020-01-01 00:00:00', 'end' => '2020-01-01 00:00:00'])
            ->execute();

        $events = $this->out->getEventListCurrent();

        self::assertNull($events);
    }

    /**
     * Tests that getEventListParticipation() returns events for a user.
     */
    public function testGetEventListParticipation()
    {
        $events = $this->out->getEventListParticipation(1);

        self::assertNotNull($events);
        self::assertCount(3, $events);
    }

    /**
     * Tests that getEventListParticipation() returns events for user 2.
     */
    public function testGetEventListParticipationUser2()
    {
        $events = $this->out->getEventListParticipation(2);

        self::assertNotNull($events);
        self::assertCount(2, $events);
    }

    /**
     * Tests that getEventListParticipation() returns null for a user with no events.
     */
    public function testGetEventListParticipationNoEvents()
    {
        $events = $this->out->getEventListParticipation(9999);

        self::assertNull($events);
    }

    /**
     * Tests that existsTable() returns true for an existing table.
     */
    public function testExistsTableTrue()
    {
        self::assertTrue($this->out->existsTable('events'));
    }

    /**
     * Tests that existsTable() returns true for events_entrants.
     */
    public function testExistsTableEntrants()
    {
        self::assertTrue($this->out->existsTable('events_entrants'));
    }

    /**
     * Tests that existsTable() returns false for a non-existent table.
     */
    public function testExistsTableFalse()
    {
        self::assertFalse($this->out->existsTable('nonexistent_table'));
    }

    /**
     * Tests that getEntriesForJson() returns null when start or end is empty.
     */
    public function testGetEntriesForJsonEmptyStart()
    {
        $result = $this->out->getEntriesForJson('', '2027-01-01');

        self::assertNull($result);
    }

    /**
     * Tests that getEntriesForJson() returns null when end is empty.
     */
    public function testGetEntriesForJsonEmptyEnd()
    {
        $result = $this->out->getEntriesForJson('2025-01-01', '');

        self::assertNull($result);
    }

    /**
     * Tests that getEntriesForJson() returns null when both are empty.
     */
    public function testGetEntriesForJsonBothEmpty()
    {
        $result = $this->out->getEntriesForJson('', '');

        self::assertNull($result);
    }

    /**
     * Tests that getEntriesForJson() returns events within the date range with show=1.
     *
     * Range 2020-01-01 to 2025-12-31 should include event 1 (show=1) and
     * exclude event 4 (show=0).
     */
    public function testGetEntriesForJsonDateRange()
    {
        $result = $this->out->getEntriesForJson('2020-01-01', '2025-12-31');

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertEquals(1, $result[0]->getId());
    }

    /**
     * Tests that getEntriesForJson() returns null when no events in range.
     */
    public function testGetEntriesForJsonNoResults()
    {
        $result = $this->out->getEntriesForJson('2030-01-01', '2030-12-31');

        self::assertNull($result);
    }

    /**
     * Tests that getListOfTypes() returns all type values.
     */
    public function testGetListOfTypes()
    {
        $types = $this->out->getListOfTypes();

        self::assertIsArray($types);
        self::assertContains('Conference', $types);
        self::assertContains('Festival', $types);
        self::assertContains('Social', $types);
        self::assertContains('Exhibition', $types);
    }

    /**
     * Tests that getListOfTypes() returns empty array when table is empty.
     */
    public function testGetListOfTypesEmptyTable()
    {
        $this->db->delete('events')->execute();

        $types = $this->out->getListOfTypes();

        self::assertIsArray($types);
        self::assertCount(0, $types);
    }

    /**
     * Tests that save() inserts a new event when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new EventModel();
        $model->setId(0);
        $model->setUserId(1);
        $model->setStart('2027-06-15 10:00:00');
        $model->setEnd('2027-06-15 18:00:00');
        $model->setTitle('New Event');
        $model->setPlace('Cologne');
        $model->setType('Workshop');
        $model->setWebsite('');
        $model->setLatLong('');
        $model->setImage('');
        $model->setText('A new event');
        $model->setCurrency(1);
        $model->setPrice('');
        $model->setPriceArt(0);
        $model->setShow(1);
        $model->setUserLimit(30);
        $model->setReadAccess('2,3');

        $this->out->save($model);

        $saved = $this->db->select('*')
            ->from('events')
            ->where(['title' => 'New Event'])
            ->execute()
            ->fetchAssoc();

        self::assertNotEmpty($saved);
        self::assertEquals('Cologne', $saved['place']);
        self::assertEquals('Workshop', $saved['type']);
    }

    /**
     * Tests that save() updates an existing event when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new EventModel();
        $model->setId(1);
        $model->setUserId(1);
        $model->setStart('2025-06-15 10:00:00');
        $model->setEnd('2025-06-15 18:00:00');
        $model->setTitle('Updated Title');
        $model->setPlace('Berlin');
        $model->setType('Conference');
        $model->setWebsite('https://techconf.example.com');
        $model->setLatLong('52.52,13.40');
        $model->setImage('');
        $model->setText('Annual tech conference');
        $model->setCurrency(1);
        $model->setPrice('50');
        $model->setPriceArt(1);
        $model->setShow(1);
        $model->setUserLimit(100);
        $model->setReadAccess('2,3');

        $this->out->save($model);

        $event = $this->out->getEventById(1);
        self::assertNotNull($event);
        self::assertEquals('Updated Title', $event->getTitle());
    }

    /**
     * Tests that save() update does not affect other events.
     */
    public function testSaveUpdatePreservesOthers()
    {
        $model = new EventModel();
        $model->setId(1);
        $model->setUserId(1);
        $model->setStart('2025-06-15 10:00:00');
        $model->setEnd('2025-06-15 18:00:00');
        $model->setTitle('Changed');
        $model->setPlace('Berlin');
        $model->setType('Conference');
        $model->setWebsite('');
        $model->setLatLong('');
        $model->setImage('');
        $model->setText('');
        $model->setCurrency(1);
        $model->setPrice('');
        $model->setPriceArt(0);
        $model->setShow(1);
        $model->setUserLimit(0);
        $model->setReadAccess('2,3');

        $this->out->save($model);

        $event2 = $this->out->getEventById(2);
        self::assertNotNull($event2);
        self::assertEquals('Music Festival', $event2->getTitle());
    }

    /**
     * Tests that delete() removes the event and its entrants.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getEventById(1));

        $entrantCount = (int) $this->db->select('COUNT(*)')
            ->from('events_entrants')
            ->where(['event_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $entrantCount);
    }

    /**
     * Tests that delete() does not affect other events.
     */
    public function testDeletePreservesOthers()
    {
        $this->out->delete(1);

        $event2 = $this->out->getEventById(2);
        self::assertNotNull($event2);
        self::assertEquals('Music Festival', $event2->getTitle());
    }

    /**
     * Tests that delete() removes associated comments.
     */
    public function testDeleteRemovesComments()
    {
        $this->out->delete(1);

        $comment = $this->db->select('*')
            ->from('comments')
            ->where(['key' => 'events/show/event/id/1'])
            ->execute()
            ->fetchAssoc();

        self::assertEmpty($comment);
    }

    /**
     * Tests that delete() for a non-existent id does not throw.
     */
    public function testDeleteNonExistent()
    {
        $this->out->delete(9999);

        $count = $this->db->select('COUNT(*)')
            ->from('events')
            ->execute()
            ->fetchCell();

        self::assertEquals(5, (int)$count);
    }

    /**
     * Tests that delImageById() clears the image field.
     */
    public function testDelImageById()
    {
        $this->db->update('events')
            ->values(['image' => '/tmp/nonexistent_image_test.jpg'])
            ->where(['id' => 1])
            ->execute();

        $this->out->delImageById(1);

        $event = $this->out->getEventById(1);
        self::assertNotNull($event);
        self::assertEquals('', $event->getImage());
    }

    /**
     * Tests that delImageById() does not throw when image is already empty.
     */
    public function testDelImageByIdAlreadyEmpty()
    {
        $this->out->delImageById(1);

        $event = $this->out->getEventById(1);
        self::assertNotNull($event);
        self::assertEquals('', $event->getImage());
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
