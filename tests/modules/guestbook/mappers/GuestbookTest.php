<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Guestbook\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Guestbook\Config\Config as ModuleConfig;
use Modules\Guestbook\Models\Entry as EntryModel;

class GuestbookTest extends DatabaseTestCase
{
    /**
     * @var Guestbook
     */
    protected Guestbook $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Guestbook();
    }

    /**
     * Tests that getEntries() returns all entries in descending id order.
     */
    public function testGetEntries()
    {
        $entries = $this->out->getEntries();

        self::assertIsArray($entries);
        self::assertCount(2, $entries);
        self::assertInstanceOf(EntryModel::class, $entries[0]);

        // Default order is id DESC, so id 2 comes first
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(1, $entries[1]->getId());
    }

    /**
     * Tests that getEntries() returns correct fields for the first entry.
     */
    public function testGetEntriesFields()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('bob@example.com', $entries[0]->getEmail());
        self::assertEquals('Nice guestbook.', $entries[0]->getText());
        self::assertEquals('Bob', $entries[0]->getName());
        self::assertEquals('https://bob.example.com', $entries[0]->getHomepage());
        self::assertEquals('2024-02-20 14:45:00', $entries[0]->getDatetime());
        self::assertFalse($entries[0]->getFree());
    }

    /**
     * Tests that getEntries() returns correct fields for the second entry.
     */
    public function testGetEntriesSecond()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(1, $entries[1]->getId());
        self::assertEquals('alice@example.com', $entries[1]->getEmail());
        self::assertEquals('Great website!', $entries[1]->getText());
        self::assertEquals('Alice', $entries[1]->getName());
        self::assertEquals('https://alice.example.com', $entries[1]->getHomepage());
        self::assertEquals('2024-01-15 10:30:00', $entries[1]->getDatetime());
        self::assertTrue($entries[1]->getFree());
    }

    /**
     * Tests that getEntries() returns an empty array when no entries exist.
     */
    public function testGetEntriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        $entries = $this->out->getEntries();

        self::assertIsArray($entries);
        self::assertEmpty($entries);
    }

    /**
     * Tests that getEntries() with a where filter works.
     */
    public function testGetEntriesWithWhere()
    {
        $entries = $this->out->getEntries(['setfree' => 1]);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Alice', $entries[0]->getName());
    }

    /**
     * Tests that getEntriesBy() returns null when no entries match.
     */
    public function testGetEntriesByReturnsNull()
    {
        $result = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($result);
    }

    /**
     * Tests that getEntriesBy() with a where filter returns matching entries.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['setfree' => 0]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() respects custom orderBy.
     */
    public function testGetEntriesByCustomOrder()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'ASC']);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests inserting a new entry via save().
     */
    public function testSaveInsert()
    {
        $model = new EntryModel();
        $model->setId(0)
            ->setEmail('carol@example.com')
            ->setText('Lovely site!')
            ->setName('Carol')
            ->setHomepage('https://carol.example.com')
            ->setDatetime('2024-06-01 09:00:00')
            ->setFree(true);

        $result = $this->out->save($model);

        self::assertGreaterThan(2, $result);

        $entries = $this->out->getEntries();
        self::assertCount(3, $entries);

        // New entry should be first (id DESC)
        self::assertEquals('Carol', $entries[0]->getName());
        self::assertEquals('carol@example.com', $entries[0]->getEmail());
        self::assertEquals('Lovely site!', $entries[0]->getText());
        self::assertTrue($entries[0]->getFree());
    }

    /**
     * Tests updating an existing entry via save().
     */
    public function testSaveUpdate()
    {
        $model = new EntryModel();
        $model->setId(1)
            ->setEmail('alice.updated@example.com')
            ->setText('Updated text!')
            ->setName('Alice Updated')
            ->setHomepage('https://alice-updated.example.com')
            ->setDatetime('2024-01-15 10:30:00')
            ->setFree(true);

        $result = $this->out->save($model);

        self::assertSame(1, $result);

        $entries = $this->out->getEntries();
        $alice = $entries[1];
        self::assertEquals('alice.updated@example.com', $alice->getEmail());
        self::assertEquals('Updated text!', $alice->getText());
        self::assertEquals('Alice Updated', $alice->getName());
    }

    /**
     * Tests that update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new EntryModel();
        $model->setId(1)
            ->setEmail('changed@example.com')
            ->setText('Changed')
            ->setName('Changed Name')
            ->setHomepage('https://changed.example.com')
            ->setDatetime('2024-01-15 10:30:00')
            ->setFree(true);

        $this->out->save($model);

        $bob = $this->out->getEntries(['id' => 2]);
        self::assertCount(1, $bob);
        self::assertEquals('Bob', $bob[0]->getName());
        self::assertEquals('bob@example.com', $bob[0]->getEmail());
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        $entries = $this->out->getEntries();
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getEntries();
        self::assertCount(2, $entries);
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getEntries();
        self::assertCount(2, $before);

        $model = new EntryModel();
        $model->setId(0)
            ->setEmail('new@example.com')
            ->setText('New entry')
            ->setName('New')
            ->setHomepage('https://new.example.com')
            ->setDatetime('2024-07-01 00:00:00')
            ->setFree(false);

        $this->out->save($model);

        $after = $this->out->getEntries();
        self::assertCount(3, $after);

        // Order is id DESC: [0]=New(3), [1]=Bob(2), [2]=Alice(1)
        self::assertEquals('Bob', $after[1]->getName());
        self::assertEquals('Alice', $after[2]->getName());
    }

    /**
     * Tests updateSetfree() with explicit value (set to 1).
     */
    public function testUpdateSetfreeExplicit()
    {
        // Entry 2 has setfree = 0, set it to 1
        $this->out->updateSetfree(2, 1);

        $entries = $this->out->getEntries(['id' => 2]);
        self::assertCount(1, $entries);
        self::assertTrue($entries[0]->getFree());
    }

    /**
     * Tests updateSetfree() with explicit value (set to 0).
     */
    public function testUpdateSetfreeExplicitZero()
    {
        // Entry 1 has setfree = 1, set it to 0
        $this->out->updateSetfree(1, 0);

        $entries = $this->out->getEntries(['id' => 1]);
        self::assertCount(1, $entries);
        self::assertFalse($entries[0]->getFree());
    }

    /**
     * Tests updateSetfree() toggle behavior (default setfree = -1).
     * Entry 2 starts with setfree=0, so toggling should set it to 1.
     */
    public function testUpdateSetfreeToggle()
    {
        // Entry 2 has setfree = 0, toggle should make it 1
        $this->out->updateSetfree(2);

        $entries = $this->out->getEntries(['id' => 2]);
        self::assertCount(1, $entries);
        self::assertTrue($entries[0]->getFree());
    }

    /**
     * Tests updateSetfree() toggle behavior on entry with setfree=1.
     * Entry 1 starts with setfree=1, so toggling should set it to 0.
     */
    public function testUpdateSetfreeToggleFromOne()
    {
        $this->out->updateSetfree(1);

        $entries = $this->out->getEntries(['id' => 1]);
        self::assertCount(1, $entries);
        self::assertFalse($entries[0]->getFree());
    }

    /**
     * Tests updateSetfree() with a GuestbookModel instance (toggle).
     */
    public function testUpdateSetfreeWithModel()
    {
        $entries = $this->out->getEntries(['id' => 2]);
        $model = $entries[0];

        // Entry 2 has setfree = 0, toggle should make it 1
        $this->out->updateSetfree($model);

        $updated = $this->out->getEntries(['id' => 2]);
        self::assertTrue($updated[0]->getFree());
    }

    /**
     * Tests updateSetfree() with a GuestbookModel instance and explicit value.
     */
    public function testUpdateSetfreeWithModelExplicit()
    {
        $entries = $this->out->getEntries(['id' => 1]);
        $model = $entries[0];

        // Entry 1 has setfree = 1, explicitly set to 0
        $this->out->updateSetfree($model, 0);

        $updated = $this->out->getEntries(['id' => 1]);
        self::assertFalse($updated[0]->getFree());
    }

    /**
     * Tests that updateSetfree() returns false for a non-existent id.
     */
    public function testUpdateSetfreeNotFound()
    {
        $result = $this->out->updateSetfree(9999);

        self::assertFalse($result);
    }

    /**
     * Tests that updateSetfree() throws for an invalid explicit value.
     */
    public function testUpdateSetfreeInvalidValue()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->out->updateSetfree(1, 5);
    }

    /**
     * Tests reset() with setfree=1 deletes only approved entries.
     */
    public function testResetBySetfree()
    {
        // Entry 1 has setfree=1, Entry 2 has setfree=0
        $this->out->reset(1);

        $entries = $this->out->getEntries();
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertFalse($entries[0]->getFree());
    }

    /**
     * Tests reset() with setfree=0 deletes only unapproved entries.
     */
    public function testResetBySetfreeZero()
    {
        $this->out->reset(0);

        $entries = $this->out->getEntries();
        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertTrue($entries[0]->getFree());
    }

    /**
     * Tests reset() with no argument truncates the table and resets auto_increment.
     *
     * NOTE: This test MUST be last in the class because TRUNCATE is DDL in MySQL
     * and cannot be rolled back. It permanently wipes the table for subsequent tests.
     */
    public function testResetAll()
    {
        $this->out->reset();

        $entries = $this->out->getEntries();
        self::assertEmpty($entries);

        // After truncate + auto_increment reset, next insert should get id 1
        $model = new EntryModel();
        $model->setId(0)
            ->setEmail('test@example.com')
            ->setText('After reset')
            ->setName('Test')
            ->setHomepage('https://test.example.com')
            ->setDatetime('2024-08-01 00:00:00')
            ->setFree(false);

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
