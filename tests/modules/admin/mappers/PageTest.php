<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Page as PageModel;

class PageTest extends DatabaseTestCase
{
    protected PageMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PageMapper();
    }

    /**
     * Tests that checkDB() returns true when both tables exist.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all page content rows.
     * Page 1 has content in two locales and therefore appears twice.
     */
    public function testGetEntriesBy()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(PageModel::class, $entries[0]);

        // Default ordering is p.id DESC.
        self::assertEquals(2, $entries[0]->getId());
        self::assertEquals('Page 2 (de)', $entries[0]->getTitle());
    }

    /**
     * Tests that getEntriesBy() respects the where clause and the ordering.
     */
    public function testGetEntriesByWithWhereAndOrder()
    {
        $entries = $this->out->getEntriesBy(['p.id' => 1], ['p.id' => 'ASC']);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);

        $titles = [];
        foreach ($entries as $entry) {
            self::assertEquals(1, $entry->getId());
            $titles[] = $entry->getTitle();
        }

        self::assertContains('Page 1 (de)', $titles);
        self::assertContains('Page 1 (en)', $titles);
    }

    /**
     * Tests that getEntriesBy() returns null when no pages exist.
     */
    public function testGetEntriesByEmpty()
    {
        $this->db->delete()->from('pages_content')->execute();
        $this->db->delete()->from('pages')->execute();

        self::assertNull($this->out->getEntriesBy());
    }

    /**
     * Tests that getEntriesBy() sets the pagination rows via found rows.
     */
    public function testGetEntriesByWithPagination()
    {
        $pagination = new Pagination();
        $entries = $this->out->getEntriesBy([], [], $pagination);

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertSame(3, $pagination->getRows());
    }

    /**
     * Tests that getPageList() returns the pages with content in the given locale.
     */
    public function testGetPageList()
    {
        $pages = $this->out->getPageList('de_DE');

        self::assertNotNull($pages);
        self::assertCount(2, $pages);

        self::assertEquals(2, $pages[0]->getId());
        self::assertEquals('Page 2 (de)', $pages[0]->getTitle());
        self::assertEquals('2024-01-02 11:00:00', $pages[0]->getDateCreated());

        self::assertEquals(1, $pages[1]->getId());
        self::assertEquals('Page 1 (de)', $pages[1]->getTitle());
    }

    /**
     * Tests that getPageList() only returns pages existing in the second locale.
     */
    public function testGetPageListSecondLocale()
    {
        $pages = $this->out->getPageList('en_EN');

        self::assertNotNull($pages);
        self::assertCount(1, $pages);
        self::assertEquals(1, $pages[0]->getId());
        self::assertEquals('Page 1 (en)', $pages[0]->getTitle());
    }

    /**
     * Tests that getPageList() returns null when no page has content in the locale.
     */
    public function testGetPageListUnknownLocale()
    {
        self::assertNull($this->out->getPageList('fr_FR'));
    }

    /**
     * Tests that getPageByIdLocale() returns all fields of the page.
     */
    public function testGetPageByIdLocale()
    {
        $page = $this->out->getPageByIdLocale(1, 'de_DE');

        self::assertNotNull($page);
        self::assertEquals(1, $page->getId());
        self::assertEquals('Page 1 (de)', $page->getTitle());
        self::assertEquals('content of page 1 (de)', $page->getContent());
        self::assertEquals('description of page 1 (de)', $page->getDescription());
        self::assertEquals('keywords of page 1 (de)', $page->getKeywords());
        self::assertEquals('page1', $page->getPerma());
        self::assertEquals('de_DE', $page->getLocale());
        self::assertEquals('2024-01-01 10:00:00', $page->getDateCreated());
    }

    /**
     * Tests that getPageByIdLocale() returns the second page.
     */
    public function testGetPageByIdLocaleSecond()
    {
        $page = $this->out->getPageByIdLocale(2, 'de_DE');

        self::assertNotNull($page);
        self::assertEquals(2, $page->getId());
        self::assertEquals('Page 2 (de)', $page->getTitle());
    }

    /**
     * Tests that getPageByIdLocale() returns null for a non-existent id.
     */
    public function testGetPageByIdLocaleNotFound()
    {
        self::assertNull($this->out->getPageByIdLocale(999, 'de_DE'));
    }

    /**
     * Tests that getPageByIdLocale() with an empty locale matches no content row.
     */
    public function testGetPageByIdLocaleEmptyLocale()
    {
        self::assertNull($this->out->getPageByIdLocale(1));
    }

    /**
     * Tests that getPagePermas() returns all permas keyed by perma.
     * Page 1 uses the same perma in both locales and therefore appears only once.
     */
    public function testGetPagePermas()
    {
        $permas = $this->out->getPagePermas();

        self::assertNotNull($permas);
        self::assertCount(2, $permas);
        self::assertArrayHasKey('page1', $permas);
        self::assertArrayHasKey('page2', $permas);
        self::assertEquals(1, $permas['page1']['page_id']);
        self::assertEquals(2, $permas['page2']['page_id']);
    }

    /**
     * Tests that getPagePermas() returns null when no pages exist.
     */
    public function testGetPagePermasEmpty()
    {
        $this->db->delete()->from('pages_content')->execute();
        $this->db->delete()->from('pages')->execute();

        self::assertNull($this->out->getPagePermas());
    }

    /**
     * Tests inserting a new page via save().
     */
    public function testSaveInsert()
    {
        $page = new PageModel();
        $page->setTitle('New Page')
            ->setContent('content of new page')
            ->setDescription('description of new page')
            ->setKeywords('new, page')
            ->setPerma('new-page')
            ->setLocale('de_DE');

        $id = $this->out->save($page);

        self::assertGreaterThan(2, $id);

        $pages = $this->db->select('id')->from('pages')->execute()->fetchList();
        self::assertContains((string) $id, $pages);

        $saved = $this->out->getPageByIdLocale($id, 'de_DE');
        self::assertNotNull($saved);
        self::assertEquals('New Page', $saved->getTitle());
        self::assertEquals('content of new page', $saved->getContent());
        self::assertEquals('description of new page', $saved->getDescription());
        self::assertEquals('new, page', $saved->getKeywords());
        self::assertEquals('new-page', $saved->getPerma());
        self::assertEquals('de_DE', $saved->getLocale());
    }

    /**
     * Tests updating an existing page via save().
     */
    public function testSaveUpdate()
    {
        $page = new PageModel();
        $page->setId(1)
            ->setTitle('Page 1 (de) updated')
            ->setContent('updated content')
            ->setDescription('updated description')
            ->setKeywords('updated')
            ->setPerma('page1')
            ->setLocale('de_DE');

        $id = $this->out->save($page);

        self::assertEquals(1, $id);

        $saved = $this->out->getPageByIdLocale(1, 'de_DE');
        self::assertNotNull($saved);
        self::assertEquals('Page 1 (de) updated', $saved->getTitle());
        self::assertEquals('updated content', $saved->getContent());
        self::assertEquals('updated description', $saved->getDescription());
        self::assertEquals('updated', $saved->getKeywords());

        // The en_EN content row must be untouched.
        $savedEn = $this->out->getPageByIdLocale(1, 'en_EN');
        self::assertNotNull($savedEn);
        self::assertEquals('Page 1 (en)', $savedEn->getTitle());
    }

    /**
     * Tests that save() adds a new content row for an existing page without creating a new page.
     */
    public function testSaveNewLocaleForExistingPage()
    {
        $page = new PageModel();
        $page->setId(2)
            ->setTitle('Page 2 (en)')
            ->setContent('content of page 2 (en)')
            ->setDescription('description of page 2 (en)')
            ->setKeywords('keywords of page 2 (en)')
            ->setPerma('page2')
            ->setLocale('en_EN');

        $id = $this->out->save($page);

        self::assertEquals(2, $id);

        $saved = $this->out->getPageByIdLocale(2, 'en_EN');
        self::assertNotNull($saved);
        self::assertEquals('Page 2 (en)', $saved->getTitle());

        // No new row in the pages table.
        $pages = $this->db->select('id')->from('pages')->execute()->fetchList();
        self::assertCount(2, $pages);
    }

    /**
     * Tests that delete() removes a page and its content rows.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);

        self::assertNull($this->out->getPageByIdLocale(1, 'de_DE'));
        self::assertNull($this->out->getPageByIdLocale(1, 'en_EN'));

        $pages = $this->out->getPageList('de_DE');
        self::assertCount(1, $pages);
        self::assertEquals(2, $pages[0]->getId());

        $permas = $this->out->getPagePermas();
        self::assertArrayNotHasKey('page1', $permas);
        self::assertArrayHasKey('page2', $permas);
    }

    /**
     * Tests that delete() on a non-existent id does not affect existing pages.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(999);

        $pages = $this->out->getPageList('de_DE');
        self::assertCount(2, $pages);
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
