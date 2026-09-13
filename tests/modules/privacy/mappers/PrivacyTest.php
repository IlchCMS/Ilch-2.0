<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Privacy\Mappers;

use Modules\Privacy\Config\Config as ModuleConfig;
use Modules\Privacy\Mappers\Privacy as PrivacyMapper;
use Modules\Privacy\Models\Privacy as PrivacyModel;
use PHPUnit\Ilch\DatabaseTestCase;

class PrivacyTest extends DatabaseTestCase
{
    /**
     * @var PrivacyMapper
     */
    protected PrivacyMapper $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new PrivacyMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all entries seeded by install SQL.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(10, $entries);
        self::assertInstanceOf(PrivacyModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns entries ordered by position ASC (default).
     */
    public function testGetEntriesByOrderAsc()
    {
        $entries = $this->out->getEntriesBy();

        self::assertCount(10, $entries);

        // All positions are 0 by default, so verify the array is consistent
        $positions = array_map(fn(PrivacyModel $e) => $e->getPosition(), $entries);
        self::assertEquals(array_fill(0, 10, 0), $positions);
    }

    /**
     * Tests that getEntriesBy() with explicit DESC order still returns all entries.
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->out->getEntriesBy([], ['position' => 'DESC']);

        self::assertNotNull($entries);
        self::assertCount(10, $entries);
    }

    /**
     * Tests that getEntriesBy() populates model fields correctly.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy(['id' => 1]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Datenschutz auf einen Blick', $entries[0]->getTitle());
        self::assertEquals('eRecht24', $entries[0]->getUrlTitle());
        self::assertEquals('https://www.e-recht24.de', $entries[0]->getUrl());
        self::assertTrue($entries[0]->getShow());
        self::assertEquals(0, $entries[0]->getPosition());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters correctly.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['title' => 'Datenschutzbeauftragter']);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(3, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() with show filter returns all visible entries.
     */
    public function testGetEntriesByWithShowFilter()
    {
        $entries = $this->out->getEntriesBy(['show' => 1]);

        self::assertNotNull($entries);
        self::assertCount(10, $entries);
    }

    /**
     * Tests that getEntriesBy() with show=0 returns null (none hidden by default).
     */
    public function testGetEntriesByShowZero()
    {
        $entries = $this->out->getEntriesBy(['show' => 0]);

        self::assertNull($entries);
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
        $this->db->delete('privacy')->execute();

        $entries = $this->out->getEntriesBy();

        self::assertNull($entries);
    }

    /**
     * Tests that getPrivacy() returns all privacy entries (wrapper around getEntriesBy).
     */
    public function testGetPrivacy()
    {
        $entries = $this->out->getPrivacy();

        self::assertNotNull($entries);
        self::assertCount(10, $entries);
        self::assertInstanceOf(PrivacyModel::class, $entries[0]);
    }

    /**
     * Tests that getPrivacy() with WHERE clause filters correctly.
     */
    public function testGetPrivacyWithWhere()
    {
        $entries = $this->out->getPrivacy(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals('Allgemeine Hinweise und Pflichtinformationen', $entries[0]->getTitle());
    }

    /**
     * Tests that getPrivacy() returns null when no rows match.
     */
    public function testGetPrivacyNoResults()
    {
        $entries = $this->out->getPrivacy(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getPrivacyById() returns the correct entry.
     */
    public function testGetPrivacyById()
    {
        $entry = $this->out->getPrivacyById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('Datenschutz auf einen Blick', $entry->getTitle());
        self::assertEquals('eRecht24', $entry->getUrlTitle());
        self::assertEquals('https://www.e-recht24.de', $entry->getUrl());
    }

    /**
     * Tests that getPrivacyById() returns the second entry correctly.
     */
    public function testGetPrivacyByIdSecond()
    {
        $entry = $this->out->getPrivacyById(2);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getId());
        self::assertEquals('Allgemeine Hinweise und Pflichtinformationen', $entry->getTitle());
    }

    /**
     * Tests that getPrivacyById() returns null for a non-existent id.
     */
    public function testGetPrivacyByIdNotFound()
    {
        $entry = $this->out->getPrivacyById(9999);

        self::assertNull($entry);
    }

    /**
     * Tests that sort() updates the position of an entry.
     */
    public function testSort()
    {
        $result = $this->out->sort(1, 5);

        self::assertTrue($result);

        $entry = $this->out->getPrivacyById(1);
        self::assertNotNull($entry);
        self::assertEquals(5, $entry->getPosition());
    }

    /**
     * Tests that sort() does not affect other entries.
     */
    public function testSortDoesNotAffectOthers()
    {
        $this->out->sort(1, 5);

        $other = $this->out->getPrivacyById(2);
        self::assertNotNull($other);
        self::assertEquals(0, $other->getPosition());
    }

    /**
     * Tests that sort() on a non-existent id does not throw.
     */
    public function testSortNotFound()
    {
        $this->out->sort(9999, 5);

        $entries = $this->out->getEntriesBy();
        self::assertCount(10, $entries);
    }

    /**
     * Tests that save() inserts a new privacy entry when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new PrivacyModel();
        $model->setId(0)
            ->setTitle('Test Privacy')
            ->setUrlTitle('test-privacy')
            ->setURL('https://example.com')
            ->setText('Test content')
            ->setShow(true)
            ->setPosition(0);

        $id = $this->out->save($model);

        self::assertGreaterThan(10, $id);

        $saved = $this->out->getPrivacyById($id);
        self::assertNotNull($saved);
        self::assertEquals('Test Privacy', $saved->getTitle());
        self::assertEquals('test-privacy', $saved->getUrlTitle());
        self::assertEquals('https://example.com', $saved->getUrl());
        self::assertEquals('Test content', $saved->getText());
        self::assertTrue($saved->getShow());
        self::assertEquals(0, $saved->getPosition());
    }

    /**
     * Tests that save() updates an existing entry when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new PrivacyModel();
        $model->setId(1)
            ->setTitle('Updated Title')
            ->setUrlTitle('updated-title')
            ->setURL('https://updated.example.com')
            ->setText('Updated content')
            ->setShow(false)
            ->setPosition(2);

        $result = $this->out->save($model);

        self::assertEquals(1, $result);

        $entry = $this->out->getPrivacyById(1);
        self::assertNotNull($entry);
        self::assertEquals('Updated Title', $entry->getTitle());
        self::assertEquals('updated-title', $entry->getUrlTitle());
        self::assertEquals('https://updated.example.com', $entry->getUrl());
        self::assertEquals('Updated content', $entry->getText());
        self::assertFalse($entry->getShow());
        self::assertEquals(2, $entry->getPosition());
    }

    /**
     * Tests that save() update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new PrivacyModel();
        $model->setId(1)
            ->setTitle('Changed')
            ->setUrlTitle('changed')
            ->setURL('https://changed.example.com')
            ->setText('Changed text')
            ->setShow(false)
            ->setPosition(0);

        $this->out->save($model);

        $other = $this->out->getPrivacyById(2);
        self::assertNotNull($other);
        self::assertEquals('Allgemeine Hinweise und Pflichtinformationen', $other->getTitle());
        self::assertTrue($other->getShow());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getEntriesBy();
        self::assertCount(10, $before);

        $model = new PrivacyModel();
        $model->setId(0)
            ->setTitle('New Entry')
            ->setUrlTitle('new-entry')
            ->setURL('https://new.example.com')
            ->setText('New text')
            ->setShow(true)
            ->setPosition(0);

        $this->out->save($model);

        $after = $this->out->getEntriesBy();
        self::assertCount(11, $after);

        // Original entries untouched
        $first = $this->out->getPrivacyById(1);
        self::assertNotNull($first);
        self::assertEquals('Datenschutz auf einen Blick', $first->getTitle());
    }

    /**
     * Tests that update() toggles the show flag (default behavior with showMan = -1).
     */
    public function testUpdateToggleShow()
    {
        // Entry 1 has show=1, so toggling should set it to 0
        $this->out->update(1);

        $entry = $this->out->getPrivacyById(1);
        self::assertNotNull($entry);
        self::assertFalse($entry->getShow());
    }

    /**
     * Tests that update() toggles show back to 1 when currently 0.
     */
    public function testUpdateToggleShowBack()
    {
        // First toggle off
        $this->out->update(1);
        // Then toggle back on
        $this->out->update(1);

        $entry = $this->out->getPrivacyById(1);
        self::assertNotNull($entry);
        self::assertTrue($entry->getShow());
    }

    /**
     * Tests that update() with explicit showMan=1 sets the value directly.
     */
    public function testUpdateWithExplicitShowManOn()
    {
        // First turn off
        $this->out->update(1);

        $entry = $this->out->getPrivacyById(1);
        self::assertFalse($entry->getShow());

        // Now explicitly set to 1
        $this->out->update(1, 1);

        $entry = $this->out->getPrivacyById(1);
        self::assertTrue($entry->getShow());
    }

    /**
     * Tests that update() with explicit showMan=0 sets the value directly.
     */
    public function testUpdateWithExplicitShowManOff()
    {
        $this->out->update(1, 0);

        $entry = $this->out->getPrivacyById(1);
        self::assertNotNull($entry);
        self::assertFalse($entry->getShow());
    }

    /**
     * Tests that update() does not affect other entries.
     */
    public function testUpdateDoesNotAffectOthers()
    {
        $this->out->update(1);

        $other = $this->out->getPrivacyById(2);
        self::assertNotNull($other);
        self::assertTrue($other->getShow());
    }

    /**
     * Tests that delete() removes a privacy entry.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);

        self::assertNull($this->out->getPrivacyById(1));

        $entries = $this->out->getEntriesBy();
        self::assertCount(9, $entries);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getEntriesBy();
        self::assertCount(10, $entries);
    }

    /**
     * Tests that delete() removes all seeded entries.
     */
    public function testDeleteAll()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->out->delete($i);
        }

        $entries = $this->out->getEntriesBy();
        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() with multiple WHERE conditions works.
     */
    public function testGetEntriesByMultipleWhere()
    {
        $entries = $this->out->getEntriesBy(['show' => 1, 'urltitle' => 'eRecht24']);

        self::assertNotNull($entries);
        self::assertCount(10, $entries);
    }

    /**
     * Tests that getEntriesBy() with a text filter works.
     */
    public function testGetEntriesByTextFilter()
    {
        $entries = $this->out->getEntriesBy(['title' => 'Newsletter']);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(7, $entries[0]->getId());
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
