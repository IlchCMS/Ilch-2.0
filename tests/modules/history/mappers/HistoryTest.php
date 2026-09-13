<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\History\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\History\Config\Config as ModuleConfig;
use Modules\History\Mappers\History as HistoryMapper;
use Modules\History\Models\History as HistoryModel;

class HistoryTest extends DatabaseTestCase
{
    /**
     * @var HistoryMapper
     */
    protected History $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new HistoryMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntries() returns all entries from the seed data.
     */
    public function testGetEntries()
    {
        $entries = $this->out->getEntries();

        self::assertNotNull($entries);
        self::assertIsArray($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(HistoryModel::class, $entries[0]);
    }

    /**
     * Tests that getEntries() returns correct fields for the first entry.
     */
    public function testGetEntriesFields()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('2020-01-15', $entries[0]->getDate());
        self::assertEquals('Founded', $entries[0]->getTitle());
        self::assertEquals('fas fa-globe', $entries[0]->getType());
        self::assertEquals('#75ce66', $entries[0]->getColor());
        self::assertEquals('The club was officially founded by five members.', $entries[0]->getText());
    }

    /**
     * Tests that getEntries() returns correct fields for the second entry.
     */
    public function testGetEntriesSecond()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals('2021-06-20', $entries[1]->getDate());
        self::assertEquals('First Tournament', $entries[1]->getTitle());
        self::assertEquals('fas fa-graduation-cap', $entries[1]->getType());
        self::assertEquals('#f0ad4e', $entries[1]->getColor());
    }

    /**
     * Tests that getEntries() returns correct fields for the third entry.
     */
    public function testGetEntriesThird()
    {
        $entries = $this->out->getEntries();

        self::assertEquals(3, $entries[2]->getId());
        self::assertEquals('2022-03-10', $entries[2]->getDate());
        self::assertEquals('New Building', $entries[2]->getTitle());
        self::assertEquals('fas fa-camera', $entries[2]->getType());
        self::assertEquals('#5bc0de', $entries[2]->getColor());
    }

    /**
     * Tests that getEntries() returns null when no entries exist.
     */
    public function testGetEntriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $entries = $this->out->getEntries();

        self::assertNull($entries);
    }

    /**
     * Tests that getEntries() filters by a where clause.
     */
    public function testGetEntriesWithWhere()
    {
        $entries = $this->out->getEntries(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('First Tournament', $entries[0]->getTitle());
    }

    /**
     * Tests that getEntries() returns null when no rows match the where clause.
     */
    public function testGetEntriesWhereNoMatch()
    {
        $entries = $this->out->getEntries(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntries() defaults to date ASC ordering.
     */
    public function testGetEntriesOrderDateAsc()
    {
        $entries = $this->out->getEntries();

        self::assertEquals('2020-01-15', $entries[0]->getDate());
        self::assertEquals('2021-06-20', $entries[1]->getDate());
        self::assertEquals('2022-03-10', $entries[2]->getDate());
    }

    /**
     * Tests that getHistorysBy() applies custom ordering.
     */
    public function testGetHistorysByOrderDesc()
    {
        $entries = $this->out->getHistorysBy([], ['id' => 'DESC']);

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(1, $entries[2]->getId());
    }

    /**
     * Tests that getHistorysBy() filters by where clause.
     */
    public function testGetHistorysByWithWhere()
    {
        $entries = $this->out->getHistorysBy(['type' => 'fas fa-globe']);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Founded', $entries[0]->getTitle());
    }

    /**
     * Tests that getHistoryById() returns the correct entry.
     */
    public function testGetHistoryById()
    {
        $entry = $this->out->getHistoryById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('2020-01-15', $entry->getDate());
        self::assertEquals('Founded', $entry->getTitle());
        self::assertEquals('fas fa-globe', $entry->getType());
        self::assertEquals('#75ce66', $entry->getColor());
        self::assertEquals('The club was officially founded by five members.', $entry->getText());
    }

    /**
     * Tests that getHistoryById() returns a different entry.
     */
    public function testGetHistoryByIdSecond()
    {
        $entry = $this->out->getHistoryById(3);

        self::assertNotNull($entry);
        self::assertEquals(3, $entry->getId());
        self::assertEquals('New Building', $entry->getTitle());
    }

    /**
     * Tests that getHistoryById() returns null for a non-existent id.
     */
    public function testGetHistoryByIdNotFound()
    {
        $entry = $this->out->getHistoryById(9999);

        self::assertNull($entry);
    }

    /**
     * Tests that save() inserts a new entry when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new HistoryModel();
        $model->setId(0)
            ->setDate('2023-11-01')
            ->setTitle('New Era')
            ->setType('fas fa-map-marker')
            ->setColor('#d9534f')
            ->setText('A brand new chapter begins.');

        $this->out->save($model);

        $entries = $this->out->getEntries();
        self::assertCount(4, $entries);

        $new = $entries[3];
        self::assertGreaterThan(3, $new->getId());
        self::assertEquals('2023-11-01', $new->getDate());
        self::assertEquals('New Era', $new->getTitle());
        self::assertEquals('fas fa-map-marker', $new->getType());
        self::assertEquals('#d9534f', $new->getColor());
        self::assertEquals('A brand new chapter begins.', $new->getText());
    }

    /**
     * Tests that save() updates an existing entry when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new HistoryModel();
        $model->setId(1)
            ->setDate('2020-02-20')
            ->setTitle('Updated Title')
            ->setType('fas fa-video')
            ->setColor('#5bc0de')
            ->setText('Updated description text.');

        $this->out->save($model);

        $entry = $this->out->getHistoryById(1);
        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('2020-02-20', $entry->getDate());
        self::assertEquals('Updated Title', $entry->getTitle());
        self::assertEquals('fas fa-video', $entry->getType());
        self::assertEquals('#5bc0de', $entry->getColor());
        self::assertEquals('Updated description text.', $entry->getText());
    }

    /**
     * Tests that save() update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new HistoryModel();
        $model->setId(1)
            ->setDate('2020-02-20')
            ->setTitle('Changed')
            ->setType('fas fa-video')
            ->setColor('#5bc0de')
            ->setText('Changed text');

        $this->out->save($model);

        $other = $this->out->getHistoryById(2);
        self::assertNotNull($other);
        self::assertEquals('First Tournament', $other->getTitle());
        self::assertEquals('fas fa-graduation-cap', $other->getType());
        self::assertEquals('#f0ad4e', $other->getColor());
    }

    /**
     * Tests that save() returns the new id on insert.
     */
    public function testSaveInsertReturnsId()
    {
        $model = new HistoryModel();
        $model->setId(0)
            ->setDate('2024-01-01')
            ->setTitle('Test Entry')
            ->setType('fas fa-globe')
            ->setColor('#75ce66')
            ->setText('Testing return value.');

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);
        self::assertInstanceOf(HistoryModel::class, $this->out->getHistoryById($newId));
    }

    /**
     * Tests that save() returns the id on update.
     */
    public function testSaveUpdateReturnsId()
    {
        $model = new HistoryModel();
        $model->setId(2)
            ->setDate('2021-07-01')
            ->setTitle('Updated')
            ->setType('fas fa-lightbulb')
            ->setColor('#f0ad4e')
            ->setText('Updated text.');

        $returnedId = $this->out->save($model);

        self::assertEquals(2, $returnedId);
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getHistoryById(1));

        $entries = $this->out->getEntries();
        self::assertCount(2, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(3, $entries[1]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not remove other entries.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getEntries();
        self::assertCount(3, $entries);
    }

    /**
     * Tests that multiple deletes remove all entries.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        self::assertNull($this->out->getEntries());
    }

    /**
     * Tests that getEntriesBy() with a date range where clause works.
     */
    public function testGetEntriesByDateRange()
    {
        $entries = $this->out->getEntriesBy(['date >=' => '2021-01-01', 'date <=' => '2021-12-31']);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('First Tournament', $entries[0]->getTitle());
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
