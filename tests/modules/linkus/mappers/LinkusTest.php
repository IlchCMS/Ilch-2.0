<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Linkus\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Linkus\Config\Config as ModuleConfig;
use Modules\Linkus\Mappers\Linkus as LinkusMapper;
use Modules\Linkus\Models\Linkus as LinkusModel;

class LinkusTest extends DatabaseTestCase
{
    /**
     * @var LinkusMapper
     */
    protected Linkus $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new LinkusMapper();
    }

    /**
     * Tests that getEntriesBy() returns all entries.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(LinkusModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns entries ordered by id DESC (default).
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->out->getEntriesBy();

        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(1, $entries[2]->getId());
    }

    /**
     * Tests that getEntriesBy() with explicit ASC order returns ascending order.
     */
    public function testGetEntriesByOrderAsc()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'ASC']);

        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(3, $entries[2]->getId());
    }

    /**
     * Tests that getEntriesBy() populates model fields correctly.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy(['id' => 1]);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Our Website', $entries[0]->getTitle());
        self::assertEquals('banner_home.html', $entries[0]->getBanner());
    }

    /**
     * Tests that getEntriesBy() handles an empty banner correctly.
     */
    public function testGetEntriesByEmptyBanner()
    {
        $entries = $this->out->getEntriesBy(['id' => 3]);

        self::assertCount(1, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals('Blog', $entries[0]->getTitle());
        self::assertEquals('', $entries[0]->getBanner());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters correctly.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['title' => 'Forum']);

        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('banner_forum.html', $entries[0]->getBanner());
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
        $this->db->delete('linkus')->execute();

        $entries = $this->out->getEntriesBy();

        self::assertNull($entries);
    }

    /**
     * Tests that getLinkus() delegates to getEntriesBy with ASC order.
     */
    public function testGetLinkusOrderAsc()
    {
        $entries = $this->out->getLinkus();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(3, $entries[2]->getId());
    }

    /**
     * Tests that getLinkus() with WHERE clause filters correctly.
     */
    public function testGetLinkusWithWhere()
    {
        $entries = $this->out->getLinkus(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals('Forum', $entries[0]->getTitle());
    }

    /**
     * Tests that getLinkus() returns null when no rows match.
     */
    public function testGetLinkusNoResults()
    {
        $entries = $this->out->getLinkus(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getLinkusById() returns the correct entry.
     */
    public function testGetLinkusById()
    {
        $entry = $this->out->getLinkusById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('Our Website', $entry->getTitle());
        self::assertEquals('banner_home.html', $entry->getBanner());
    }

    /**
     * Tests that getLinkusById() returns null for a non-existent id.
     */
    public function testGetLinkusByIdNotFound()
    {
        $entry = $this->out->getLinkusById(9999);

        self::assertNull($entry);
    }

    /**
     * Tests that getLinkusById() returns the entry with an empty banner.
     */
    public function testGetLinkusByIdEmptyBanner()
    {
        $entry = $this->out->getLinkusById(3);

        self::assertNotNull($entry);
        self::assertEquals('Blog', $entry->getTitle());
        self::assertEquals('', $entry->getBanner());
    }

    /**
     * Tests that save() inserts a new entry when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new LinkusModel();
        $model->setId(0);
        $model->setTitle('New Page');
        $model->setBanner('banner_new.html');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getLinkusById($id);
        self::assertNotNull($saved);
        self::assertEquals('New Page', $saved->getTitle());
        self::assertEquals('banner_new.html', $saved->getBanner());
    }

    /**
     * Tests that save() inserts with an empty banner.
     */
    public function testSaveInsertEmptyBanner()
    {
        $model = new LinkusModel();
        $model->setId(0);
        $model->setTitle('No Banner');
        $model->setBanner('');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getLinkusById($id);
        self::assertNotNull($saved);
        self::assertEquals('', $saved->getBanner());
    }

    /**
     * Tests that save() updates an existing entry when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new LinkusModel();
        $model->setId(1);
        $model->setTitle('Updated Website');
        $model->setBanner('banner_updated.html');

        $id = $this->out->save($model);

        self::assertEquals(1, $id);

        $saved = $this->out->getLinkusById(1);
        self::assertNotNull($saved);
        self::assertEquals('Updated Website', $saved->getTitle());
        self::assertEquals('banner_updated.html', $saved->getBanner());
    }

    /**
     * Tests that save() update does not affect other entries.
     */
    public function testSaveUpdatePreservesOthers()
    {
        $model = new LinkusModel();
        $model->setId(1);
        $model->setTitle('Changed');
        $model->setBanner('banner_changed.html');

        $this->out->save($model);

        $entry2 = $this->out->getLinkusById(2);
        self::assertEquals('Forum', $entry2->getTitle());
        self::assertEquals('banner_forum.html', $entry2->getBanner());
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getLinkusById(1));

        $remaining = $this->out->getLinkus();
        self::assertCount(2, $remaining);
    }

    /**
     * Tests that delete() does not throw for a non-existent id.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $entries = $this->out->getLinkus();
        self::assertCount(3, $entries);
    }

    /**
     * Tests that delete() removes the last remaining entry.
     */
    public function testDeleteLastEntry()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $entries = $this->out->getLinkus();
        self::assertNull($entries);
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
