<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;

class BoxTest extends DatabaseTestCase
{
    /**
     * @var BoxMapper
     */
    protected Box $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new BoxMapper();
    }

    /**
     * Tests that checkDBSelfBox() returns true if the self box tables exist.
     */
    public function testCheckDBSelfBox()
    {
        self::assertTrue($this->out->checkDBSelfBox());
    }

    /**
     * Tests that checkDB() returns true if the module box table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getSelfBoxEntriesBy() returns all self box entries.
     */
    public function testGetSelfBoxEntriesBy()
    {
        $entries = $this->out->getSelfBoxEntriesBy();

        // Box 1 has two locales (two content rows) and box 2 has one locale.
        self::assertIsArray($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(BoxModel::class, $entries[0]);

        // Ordered by b.id DESC: box 2 comes before box 1.
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals(1, $entries[1]->getId());
        self::assertEquals(1, $entries[2]->getId());

        // The two rows of box 1 carry its two locales (row order within one box id is not part of the contract).
        $box1Locales = [$entries[1]->getLocale(), $entries[2]->getLocale()];
        sort($box1Locales);
        self::assertSame(['de_DE', 'en_EN'], $box1Locales);

        self::assertEquals('2024-01-02 11:00:00', $entries[0]->getDateCreated());
    }

    /**
     * Tests that getSelfBoxEntriesBy() returns an empty array when no entries exist.
     */
    public function testGetSelfBoxEntriesByEmpty()
    {
        // Delete all boxes
        $this->out->delete(1);
        $this->out->delete(2);

        $entries = $this->out->getSelfBoxEntriesBy();

        // The method's contract is array, so an empty list is [], not null.
        self::assertIsArray($entries);
        self::assertCount(0, $entries);
    }

    /**
     * Tests that rows of the same box are NOT merged, even when their titles are identical.
     * Each (box, locale) content row is returned as its own row (no GROUP BY on purpose).
     */
    public function testGetSelfBoxEntriesByKeepsRowsWithSameTitle()
    {
        $model = new BoxModel();
        $model->setId(2)
            ->setLocale('en_EN')
            ->setTitle('title of box 2 (de)') // identical title to the de_DE row
            ->setContent('content of box 2 (en)');

        $this->out->save($model);

        $entries = $this->out->getSelfBoxEntriesBy();

        // Box 1 has 2 locales and box 2 now has 2 locales as well -> 4 rows.
        self::assertCount(4, $entries);

        $box2 = [];
        foreach ($entries as $entry) {
            if ($entry->getId() === 2) {
                $box2[$entry->getLocale()] = $entry;
            }
        }

        // Both locale rows of box 2 survived, with their own title/content.
        $locales = array_keys($box2);
        sort($locales);
        self::assertSame(['de_DE', 'en_EN'], $locales);
        self::assertEquals('title of box 2 (de)', $box2['de_DE']->getTitle());
        self::assertEquals('content of box 2 (en)', $box2['en_EN']->getContent());
    }

    /**
     * Tests that getSelfBoxList() filters by locale.
     */
    public function testGetSelfBoxList()
    {
        $deEntries = $this->out->getSelfBoxList('de_DE');

        self::assertCount(2, $deEntries);

        // Ordered by b.id DESC: box 2 comes before box 1.
        self::assertEquals(2, $deEntries[0]->getId());
        self::assertEquals('title of box 2 (de)', $deEntries[0]->getTitle());
        self::assertEquals(1, $deEntries[1]->getId());
        self::assertEquals('title of box 1 (de)', $deEntries[1]->getTitle());

        $enEntries = $this->out->getSelfBoxList('en_EN');

        self::assertCount(1, $enEntries);
        self::assertEquals(1, $enEntries[0]->getId());
    }

    /**
     * Tests that getSelfBoxByIdLocale() returns the correct entry.
     */
    public function testGetSelfBoxByIdLocale()
    {
        $entry = $this->out->getSelfBoxByIdLocale(1, 'de_DE');

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('title of box 1 (de)', $entry->getTitle());
        self::assertEquals('content of box 1 (de)', $entry->getContent());
        self::assertEquals('de_DE', $entry->getLocale());

        $entry = $this->out->getSelfBoxByIdLocale(1, 'en_EN');

        self::assertNotNull($entry);
        self::assertEquals('title of box 1 (en)', $entry->getTitle());
        self::assertEquals('content of box 1 (en)', $entry->getContent());
    }

    /**
     * Tests that getSelfBoxByIdLocale() returns null when no entry exists.
     */
    public function testGetSelfBoxByIdLocaleNotFound()
    {
        // Box 2 has no en_EN content row.
        self::assertNull($this->out->getSelfBoxByIdLocale(2, 'en_EN'));

        // Box 9999 does not exist at all.
        self::assertNull($this->out->getSelfBoxByIdLocale(9999, 'de_DE'));
    }

    /**
     * Tests that save() updates existing content.
     */
    public function testSaveUpdate()
    {
        $model = new BoxModel();
        $model->setId(1)
            ->setLocale('de_DE')
            ->setTitle('updated title')
            ->setContent('updated content');

        self::assertEquals(1, $this->out->save($model));

        $entry = $this->out->getSelfBoxByIdLocale(1, 'de_DE');
        self::assertNotNull($entry);
        self::assertEquals('updated title', $entry->getTitle());
        self::assertEquals('updated content', $entry->getContent());
    }

    /**
     * Tests that save() inserts a new locale for an existing box.
     */
    public function testSaveInsertNewLocale()
    {
        $model = new BoxModel();
        $model->setId(1)
            ->setLocale('fr_FR')
            ->setTitle('titre de la box 1')
            ->setContent('contenu de la box 1');

        self::assertEquals(1, $this->out->save($model));

        $entry = $this->out->getSelfBoxByIdLocale(1, 'fr_FR');
        self::assertNotNull($entry);
        self::assertEquals('titre de la box 1', $entry->getTitle());
        self::assertEquals('contenu de la box 1', $entry->getContent());
    }

    /**
     * Tests that save() creates a new box when no id is set.
     */
    public function testSaveInsert()
    {
        $model = new BoxModel();
        $model->setLocale('de_DE')
            ->setTitle('title of new box')
            ->setContent('content of new box');

        $boxId = $this->out->save($model);

        // The auto-increment id is not reset between tests.
        self::assertGreaterThan(2, $boxId);

        $entry = $this->out->getSelfBoxByIdLocale($boxId, 'de_DE');
        self::assertNotNull($entry);
        self::assertEquals($boxId, $entry->getId());
        self::assertEquals('title of new box', $entry->getTitle());
        self::assertEquals('content of new box', $entry->getContent());

        // save() updates the model with the new id, so callers can use it right away.
        self::assertEquals($boxId, $model->getId());
    }

    /**
     * Tests that delete() removes a box and its content.
     */
    public function testDelete()
    {
        self::assertTrue($this->out->delete(1));

        self::assertNull($this->out->getSelfBoxByIdLocale(1, 'de_DE'));
        self::assertNull($this->out->getSelfBoxByIdLocale(1, 'en_EN'));

        // Remaining box should still be present
        self::assertNotNull($this->out->getSelfBoxByIdLocale(2, 'de_DE'));
    }

    /**
     * Tests that delete() on a non-existent id returns false.
     */
    public function testDeleteNotFound()
    {
        self::assertFalse($this->out->delete(9999));

        // Existing boxes should be unaffected
        $entries = $this->out->getSelfBoxEntriesBy();
        self::assertCount(3, $entries);
    }

    /**
     * Tests that install() inserts module box entries.
     */
    public function testInstall()
    {
        $model = new BoxModel();
        $model->setModule('admin');
        $model->addContent('testBoxKey', [
            'de_DE' => ['name' => 'TestBoxName'],
            'en_EN' => ['name' => 'TestBoxName EN'],
        ]);

        self::assertTrue($this->out->install($model));

        $entry = $this->out->getBoxByIdLocale('testBoxKey', 'de_DE');
        self::assertNotNull($entry);
        self::assertEquals('admin', $entry->getModule());
        self::assertEquals('testBoxKey', $entry->getKey());
        self::assertEquals('TestBoxName', $entry->getName());

        $entry = $this->out->getBoxByIdLocale('testBoxKey', 'en_EN');
        self::assertNotNull($entry);
        self::assertEquals('TestBoxName EN', $entry->getName());
    }

    /**
     * Tests that install() returns false if content is not an array.
     */
    public function testInstallWithNonArrayContent()
    {
        $model = new BoxModel();
        $model->setModule('admin');

        self::assertFalse($this->out->install($model));
    }

    /**
     * Tests that modulesBoxExists() returns true for an existing module box.
     */
    public function testModulesBoxExists()
    {
        self::assertTrue($this->out->modulesBoxExists('langswitch', 'admin'));
    }

    /**
     * Tests that modulesBoxExists() returns false if module or key do not match.
     */
    public function testModulesBoxExistsNotFound()
    {
        self::assertFalse($this->out->modulesBoxExists('langswitch', 'comment'));
        self::assertFalse($this->out->modulesBoxExists('doesNotExist', 'admin'));
    }

    /**
     * Tests that getBoxList() returns all module boxes for a locale.
     */
    public function testGetBoxList()
    {
        $boxes = $this->out->getBoxList('de_DE');

        self::assertNotNull($boxes);
        self::assertCount(2, $boxes);
        self::assertInstanceOf(BoxModel::class, $boxes[0]);

        $names = [];
        foreach ($boxes as $box) {
            $names[] = $box->getName();
        }
        self::assertContains('Sprachauswahl', $names);
        self::assertContains('Letzter Krieg', $names);
    }

    /**
     * Tests that getBoxByIdLocale() returns the correct box.
     */
    public function testGetBoxByIdLocale()
    {
        $box = $this->out->getBoxByIdLocale('langswitch', 'en_EN');

        self::assertNotNull($box);
        self::assertEquals('langswitch', $box->getKey());
        self::assertEquals('admin', $box->getModule());
        self::assertEquals('Language selection', $box->getName());
    }

    /**
     * Tests that getBoxByIdLocale() returns null when no box exists.
     */
    public function testGetBoxByIdLocaleNotFound()
    {
        // lastwar has no en_EN entry.
        self::assertNull($this->out->getBoxByIdLocale('lastwar', 'en_EN'));

        // Key does not exist at all.
        self::assertNull($this->out->getBoxByIdLocale('doesNotExist', 'de_DE'));
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
