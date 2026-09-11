<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Imprint\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Imprint\Config\Config as ModuleConfig;
use Modules\Imprint\Mappers\Imprint as ImprintMapper;
use Modules\Imprint\Models\Imprint as ImprintModel;

class ImprintTest extends DatabaseTestCase
{
    /**
     * @var ImprintMapper
     */
    protected Imprint $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new ImprintMapper();
    }

    /**
     * Tests that getEntriesBy() returns all imprint entries.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(ImprintModel::class, $entries[0]);
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
     * Tests that getEntriesBy() with orderBy ASC returns ascending order.
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
        $entries = $this->out->getEntriesBy(['id' => 2]);

        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(
            '<h1>Custom Imprint</h1><p>Company GmbH<br />Musterstr. 1, 12345 City</p>',
            $entries[0]->getImprint()
        );
    }

    /**
     * Tests that getEntriesBy() handles the empty-string imprint correctly.
     */
    public function testGetEntriesByEmptyImprint()
    {
        $entries = $this->out->getEntriesBy(['id' => 1]);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('', $entries[0]->getImprint());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters correctly.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['id' => 3]);

        self::assertCount(1, $entries);
        self::assertEquals('Simple plain-text imprint for testing.', $entries[0]->getImprint());
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
     * Tests that getImprint() delegates to getEntriesBy() and returns all entries.
     */
    public function testGetImprint()
    {
        $entries = $this->out->getImprint();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
    }

    /**
     * Tests that getImprint() with WHERE clause filters correctly.
     */
    public function testGetImprintWithWhere()
    {
        $entries = $this->out->getImprint(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that getImprint() returns null when no rows match.
     */
    public function testGetImprintNoResults()
    {
        $entries = $this->out->getImprint(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getImprintById() returns the correct entry.
     */
    public function testGetImprintById()
    {
        $entry = $this->out->getImprintById(2);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getId());
        self::assertEquals(
            '<h1>Custom Imprint</h1><p>Company GmbH<br />Musterstr. 1, 12345 City</p>',
            $entry->getImprint()
        );
    }

    /**
     * Tests that getImprintById() returns null for a non-existent id.
     */
    public function testGetImprintByIdNotFound()
    {
        $entry = $this->out->getImprintById(9999);

        self::assertNull($entry);
    }

    /**
     * Tests that getImprintById() returns the empty-imprint entry (id=1).
     */
    public function testGetImprintByIdEmptyContent()
    {
        $entry = $this->out->getImprintById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('', $entry->getImprint());
    }

    /**
     * Tests that save() inserts a new entry when the id does not exist.
     */
    public function testSaveInsert()
    {
        $model = new ImprintModel();
        $model->setId(0);
        $model->setImprint('Newly created imprint content.');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        // Verify the row was created
        $saved = $this->out->getImprintById($id);
        self::assertNotNull($saved);
        self::assertEquals('Newly created imprint content.', $saved->getImprint());
    }

    /**
     * Tests that save() updates an existing entry when the id matches.
     */
    public function testSaveUpdate()
    {
        $model = new ImprintModel();
        $model->setId(2);
        $model->setImprint('Updated imprint HTML content.');

        $id = $this->out->save($model);

        self::assertEquals(2, $id);

        $saved = $this->out->getImprintById(2);
        self::assertNotNull($saved);
        self::assertEquals('Updated imprint HTML content.', $saved->getImprint());
    }

    /**
     * Tests that save() does not affect other entries on update.
     */
    public function testSaveUpdatePreservesOthers()
    {
        $model = new ImprintModel();
        $model->setId(1);
        $model->setImprint('Changed row 1.');

        $this->out->save($model);

        // Row 2 should remain unchanged
        $row2 = $this->out->getImprintById(2);
        self::assertEquals(
            '<h1>Custom Imprint</h1><p>Company GmbH<br />Musterstr. 1, 12345 City</p>',
            $row2->getImprint()
        );
    }

    /**
     * Tests that save() with an empty imprint string updates successfully.
     */
    public function testSaveUpdateEmptyImprint()
    {
        $model = new ImprintModel();
        $model->setId(3);
        $model->setImprint('');

        $id = $this->out->save($model);

        self::assertEquals(3, $id);

        $saved = $this->out->getImprintById(3);
        self::assertEquals('', $saved->getImprint());
    }

    /**
     * Tests that set() inserts a new entry when called without an id.
     */
    public function testSetWithoutId()
    {
        $id = $this->out->set('Brand new imprint via set()');

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getImprintById($id);
        self::assertNotNull($saved);
        self::assertEquals('Brand new imprint via set()', $saved->getImprint());
    }

    /**
     * Tests that set() updates an existing entry when called with an id.
     */
    public function testSetWithId()
    {
        $id = $this->out->set('Overwritten content.', 2);

        self::assertEquals(2, $id);

        $saved = $this->out->getImprintById(2);
        self::assertEquals('Overwritten content.', $saved->getImprint());
    }

    /**
     * Tests that set() with an empty string value works.
     */
    public function testSetEmptyString()
    {
        $id = $this->out->set('', 1);

        self::assertEquals(1, $id);

        $saved = $this->out->getImprintById(1);
        self::assertEquals('', $saved->getImprint());
    }

    /**
     * Tests that the imprint field can store a large MEDIUMTEXT payload.
     */
    public function testSaveLargeContent()
    {
        // MEDIUMTEXT supports up to ~16MB; use a realistic large HTML block.
        $largeContent = str_repeat('<p>Paragraph of legal text for testing purposes. </p>', 1000);

        $id = $this->out->set($largeContent, 3);

        self::assertEquals(3, $id);

        $saved = $this->out->getImprintById(3);
        self::assertEquals($largeContent, $saved->getImprint());
        self::assertGreaterThan(10000, strlen($saved->getImprint()));
    }

    /**
     * Tests that checkDB() returns true when the imprint table exists.
     */
    public function testCheckDBTrue()
    {
        self::assertTrue($this->out->checkDB());
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
