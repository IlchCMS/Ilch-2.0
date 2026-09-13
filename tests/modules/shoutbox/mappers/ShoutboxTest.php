<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shoutbox\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Shoutbox\Config\Config as ModuleConfig;
use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;

class ShoutboxTest extends DatabaseTestCase
{
    /**
     * @var ShoutboxMapper
     */
    protected ShoutboxMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ShoutboxMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getShoutbox() returns all entries in descending id order.
     */
    public function testGetShoutbox()
    {
        $entries = $this->out->getShoutbox();

        self::assertIsArray($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(ShoutboxModel::class, $entries[0]);

        // Default order is id DESC, so id 3 comes first
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(1, $entries[2]->getId());
    }

    /**
     * Tests that getShoutbox() returns correct fields for the first entry.
     */
    public function testGetShoutboxFields()
    {
        $entries = $this->out->getShoutbox();

        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(0, $entries[0]->getUid());
        self::assertEquals('Guest', $entries[0]->getName());
        self::assertEquals('Just passing by.', $entries[0]->getTextarea());
        self::assertEquals('2024-03-10 08:00:00', $entries[0]->getTime());
    }

    /**
     * Tests that getShoutbox() returns correct fields for the second entry.
     */
    public function testGetShoutboxSecond()
    {
        $entries = $this->out->getShoutbox();

        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(2, $entries[1]->getUid());
        self::assertEquals('Bob', $entries[1]->getName());
        self::assertEquals('Nice shoutbox!', $entries[1]->getTextarea());
        self::assertEquals('2024-02-20 14:45:00', $entries[1]->getTime());
    }

    /**
     * Tests that getShoutbox() returns an empty array when no entries exist.
     */
    public function testGetShoutboxEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $entries = $this->out->getShoutbox();

        self::assertIsArray($entries);
        self::assertEmpty($entries);
    }

    /**
     * Tests that getShoutboxLimit() returns only the specified number of entries.
     */
    public function testGetShoutboxLimit()
    {
        $entries = $this->out->getShoutboxLimit(2);

        self::assertIsArray($entries);
        self::assertCount(2, $entries);

        // Ordered by id DESC: [0]=3, [1]=2
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests that getShoutboxLimit() with no limit returns all entries.
     */
    public function testGetShoutboxLimitNull()
    {
        $entries = $this->out->getShoutboxLimit();

        self::assertIsArray($entries);
        self::assertCount(3, $entries);
    }

    /**
     * Tests that getShoutboxLimit() with limit 1 returns only the latest entry.
     */
    public function testGetShoutboxLimitOne()
    {
        $entries = $this->out->getShoutboxLimit(1);

        self::assertCount(1, $entries);
        self::assertEquals(3, $entries[0]->getId());
    }

    /**
     * Tests that getEntryById() returns the correct entry.
     */
    public function testGetEntryById()
    {
        $entry = $this->out->getEntryById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals(1, $entry->getUid());
        self::assertEquals('Alice', $entry->getName());
        self::assertEquals('Hello world!', $entry->getTextarea());
        self::assertEquals('2024-01-15 10:30:00', $entry->getTime());
    }

    /**
     * Tests that getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        $entry = $this->out->getEntryById(9999);

        self::assertNull($entry);
    }

    /**
     * Tests that getEntriesBy() with a where filter works.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 2]);

        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('Bob', $entries[0]->getName());
    }

    /**
     * Tests that getEntriesBy() returns empty array when no entries match.
     */
    public function testGetEntriesByEmpty()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 9999]);

        self::assertIsArray($entries);
        self::assertEmpty($entries);
    }

    /**
     * Tests that getEntriesBy() respects custom orderBy.
     */
    public function testGetEntriesByCustomOrder()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'ASC']);

        self::assertCount(3, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(3, $entries[2]->getId());
    }

    /**
     * Tests that getEntriesBy() with search filters by name.
     */
    public function testGetEntriesBySearchName()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'DESC'], null, 'Alice');

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Alice', $entries[0]->getName());
    }

    /**
     * Tests that getEntriesBy() with search filters by textarea.
     */
    public function testGetEntriesBySearchTextarea()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'DESC'], null, 'passing');

        self::assertCount(1, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals('Guest', $entries[0]->getName());
    }

    /**
     * Tests that getEntriesBy() with search returns empty array when no match.
     */
    public function testGetEntriesBySearchNoMatch()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'DESC'], null, 'nonexistenttext');

        self::assertIsArray($entries);
        self::assertEmpty($entries);
    }

    /**
     * Tests that getUsersOfEntries() returns an empty array for an empty input.
     */
    public function testGetUsersOfEntriesEmpty()
    {
        $users = $this->out->getUsersOfEntries([]);

        self::assertIsArray($users);
        self::assertEmpty($users);
    }

    /**
     * Tests that getUsersOfEntries() returns an empty array when all entries are guest (uid=0).
     */
    public function testGetUsersOfEntriesAllGuest()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 0]);

        $users = $this->out->getUsersOfEntries($entries);

        self::assertIsArray($users);
        self::assertEmpty($users);
    }

    /**
     * Tests that getLastPostTimeOfUser() returns the time of the latest entry.
     */
    public function testGetLastPostTimeOfUser()
    {
        $time = $this->out->getLastPostTimeOfUser(1);

        self::assertNotNull($time);
        self::assertEquals('2024-01-15 10:30:00', $time);
    }

    /**
     * Tests that getLastPostTimeOfUser() returns null when the user has no entries.
     */
    public function testGetLastPostTimeOfUserNotFound()
    {
        $time = $this->out->getLastPostTimeOfUser(9999);

        self::assertNull($time);
    }

    /**
     * Tests that getLastPostTimeOfUser() returns the latest entry when user has multiple.
     */
    public function testGetLastPostTimeOfUserMultiple()
    {
        // Insert another entry for user 1 with a later time
        $model = new ShoutboxModel();
        $model->setId(0)
            ->setUid(1)
            ->setName('Alice')
            ->setTextarea('Second message')
            ->setTime('2024-04-01 12:00:00');

        $this->out->save($model);

        $time = $this->out->getLastPostTimeOfUser(1);

        self::assertEquals('2024-04-01 12:00:00', $time);
    }

    /**
     * Tests inserting a new entry via save().
     */
    public function testSaveInsert()
    {
        $model = new ShoutboxModel();
        $model->setId(0)
            ->setUid(3)
            ->setName('Carol')
            ->setTextarea('Lovely shoutbox!')
            ->setTime('2024-06-01 09:00:00');

        $result = $this->out->save($model);

        self::assertGreaterThan(3, $result);

        $entries = $this->out->getShoutbox();
        self::assertCount(4, $entries);

        // New entry should be first (id DESC)
        self::assertEquals('Carol', $entries[0]->getName());
        self::assertEquals(3, $entries[0]->getUid());
        self::assertEquals('Lovely shoutbox!', $entries[0]->getTextarea());
        self::assertEquals('2024-06-01 09:00:00', $entries[0]->getTime());
    }

    /**
     * Tests updating an existing entry via save().
     */
    public function testSaveUpdate()
    {
        $model = new ShoutboxModel();
        $model->setId(1)
            ->setUid(1)
            ->setName('Alice Updated')
            ->setTextarea('Updated message!')
            ->setTime('2024-01-15 11:00:00');

        $result = $this->out->save($model);

        self::assertSame(1, $result);

        $entry = $this->out->getEntryById(1);
        self::assertNotNull($entry);
        self::assertEquals('Alice Updated', $entry->getName());
        self::assertEquals('Updated message!', $entry->getTextarea());
        self::assertEquals('2024-01-15 11:00:00', $entry->getTime());
    }

    /**
     * Tests that update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ShoutboxModel();
        $model->setId(1)
            ->setUid(1)
            ->setName('Changed')
            ->setTextarea('Changed text')
            ->setTime('2024-01-15 10:30:00');

        $this->out->save($model);

        $bob = $this->out->getEntryById(2);
        self::assertNotNull($bob);
        self::assertEquals('Bob', $bob->getName());
        self::assertEquals('Nice shoutbox!', $bob->getTextarea());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getShoutbox();
        self::assertCount(3, $before);

        $model = new ShoutboxModel();
        $model->setId(0)
            ->setUid(0)
            ->setName('New Guest')
            ->setTextarea('New entry')
            ->setTime('2024-07-01 00:00:00');

        $this->out->save($model);

        $after = $this->out->getShoutbox();
        self::assertCount(4, $after);

        // Original entries untouched
        // Order is id DESC: [0]=New Guest(4), [1]=Guest(3), [2]=Bob(2), [3]=Alice(1)
        self::assertEquals('Bob', $after[2]->getName());
        self::assertEquals('Alice', $after[3]->getName());
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $this->out->delete(2);

        self::assertNull($this->out->getEntryById(2));

        $entries = $this->out->getShoutbox();
        self::assertCount(2, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(1, $entries[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getShoutbox();
        self::assertCount(3, $entries);
    }

    /**
     * Tests that truncate() removes all entries and resets auto_increment.
     *
     * NOTE: This test MUST be last in the class because TRUNCATE is DDL in MySQL
     * and cannot be rolled back.
     */
    public function testTruncate()
    {
        $this->out->truncate();

        $entries = $this->out->getShoutbox();
        self::assertEmpty($entries);

        // After truncate + auto_increment reset, next insert should get id 1
        $model = new ShoutboxModel();
        $model->setId(0)
            ->setUid(1)
            ->setName('Test')
            ->setTextarea('After truncate')
            ->setTime('2024-08-01 00:00:00');

        $newId = $this->out->save($model);
        self::assertSame(1, $newId);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
