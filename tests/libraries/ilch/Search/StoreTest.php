<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Search\Config\Config as SearchModuleConfig;

class StoreTest extends DatabaseTestCase
{
    protected Store $out;

    protected function setUp(): void
    {
        parent::setUp();

        $this->out = new Store($this->db);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new SearchModuleConfig();

        return $config->getInstallSql()
            . 'ALTER TABLE [prefix]_search_documents ADD FULLTEXT KEY ft_title_body (title, body);';
    }

    /**
     * Base document for the upsert() tests, overridable per case.
     *
     * @param array $overrides
     * @return array
     */
    private function doc(array $overrides = []): array
    {
        return array_merge([
            'module'       => 'forum',
            'docType'      => 'post',
            'docId'        => 42,
            'locale'       => '',
            'parentId'     => 101,
            'scopeKey'     => 'forum_id',
            'scopeId'      => 11,
            'title'        => 'Test post',
            'body'         => 'Test body text',
            'accessGroups' => '',
            'dateActivity' => '2025-01-01 12:00:00',
            'meta'         => null,
            'contentHash'  => 'hash-42',
        ], $overrides);
    }

    /**
     * Tests that the documents table exists after provisioning.
     */
    public function testDocumentsTableExists()
    {
        self::assertTrue($this->out->exists());
    }

    /**
     * Tests that upsert() inserts a new document row.
     */
    public function testUpsertInsertsDocument()
    {
        $this->out->upsert($this->doc());

        $docs = $this->out->getDocsByIds('forum', 'post', [42]);
        self::assertCount(1, $docs);
        self::assertSame('Test post', $docs[42]['title']);
        self::assertSame('forum_id', $docs[42]['scope_key']);
        self::assertSame(11, (int)$docs[42]['scope_id']);
        self::assertSame(101, (int)$docs[42]['parent_id']);
        self::assertSame('2025-01-01 12:00:00', $docs[42]['date_activity']);
    }

    /**
     * Tests that upsert() on the same document row updates in place.
     */
    public function testUpsertIsIdempotent()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['title' => 'Updated title', 'contentHash' => 'hash-42b']));

        $docs = $this->out->getDocsByIds('forum', 'post', [42]);
        self::assertCount(1, $docs);
        self::assertSame('Updated title', $docs[42]['title']);
    }

    /**
     * Tests that upsert() keeps one row per locale.
     */
    public function testUpsertSeparatesLocales()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['locale' => 'de', 'title' => 'Deutscher Beitrag']));

        // getDocsByIds() keys rows by doc_id by design (one row per id), so
        // the locale split is asserted on the raw table instead.
        $docs = $this->db->queryArray(
            'SELECT locale, title FROM [prefix]_search_documents WHERE module = ' . $this->db->escape('forum', true)
            . ' AND doc_type = ' . $this->db->escape('post', true)
            . ' AND doc_id = 42 ORDER BY locale'
        );
        $byLocale = [];
        foreach ($docs as $row) {
            $byLocale[(string)$row['locale']] = $row['title'];
        }

        self::assertSame(['' => 'Test post', 'de' => 'Deutscher Beitrag'], $byLocale);
    }

    /**
     * Tests deleteDoc() with and without a locale restriction.
     */
    public function testDeleteDoc()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['locale' => 'de']));

        self::assertSame(1, $this->out->deleteDoc('forum', 'post', 42, 'de'));
        self::assertCount(1, $this->out->getDocsByIds('forum', 'post', [42]));

        self::assertSame(1, $this->out->deleteDoc('forum', 'post', 42, ''));
        self::assertSame([], $this->out->getDocsByIds('forum', 'post', [42]));
    }

    /**
     * Tests deleteByScope() removes all documents of one scope.
     */
    public function testDeleteByScope()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['docId' => 43]));
        $this->out->upsert($this->doc(['docId' => 44, 'scopeId' => 12]));

        self::assertSame(2, $this->out->deleteByScope('forum', 'forum_id', 11));

        $remaining = $this->out->getDocsByIds('forum', 'post', [42, 43, 44]);
        self::assertCount(1, $remaining);
        self::assertArrayHasKey(44, $remaining);
    }

    /**
     * Tests deleteByParent() removes all child documents of one parent.
     */
    public function testDeleteByParent()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['docId' => 43]));
        $this->out->upsert($this->doc(['docId' => 44, 'parentId' => 102]));

        self::assertSame(2, $this->out->deleteByParent('forum', 'post', 101));

        $remaining = $this->out->getDocsByIds('forum', 'post', [42, 43, 44]);
        self::assertCount(1, $remaining);
        self::assertArrayHasKey(44, $remaining);
    }

    /**
     * Tests deleteByModule() removes all documents of one module.
     */
    public function testDeleteByModule()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['module' => 'article', 'docType' => 'article', 'docId' => 900]));

        self::assertSame(1, $this->out->deleteByModule('article'));
        self::assertCount(1, $this->out->getDocsByIds('forum', 'post', [42]));
    }

    /**
     * Tests countDocumentsByModule() groups rows by module.
     */
    public function testCountDocumentsByModule()
    {
        $this->out->upsert($this->doc());
        $this->out->upsert($this->doc(['docId' => 43]));
        $this->out->upsert($this->doc(['module' => 'article', 'docType' => 'article', 'docId' => 900]));

        self::assertEqualsCanonicalizing(['forum' => 2, 'article' => 1], $this->out->countDocumentsByModule());
    }

    /**
     * Tests getDocsByIds() returns [] for an empty id list.
     */
    public function testGetDocsByIdsWithEmptyIds()
    {
        self::assertSame([], $this->out->getDocsByIds('forum', 'post', []));
    }

    /**
     * Tests the frontier: starts at 0, only moves forward, resets to 0.
     */
    public function testFrontierNeverMovesBackwards()
    {
        self::assertSame(0, $this->out->getFrontier('forum'));

        $this->out->setFrontier('forum', 50);
        self::assertSame(50, $this->out->getFrontier('forum'));

        $this->out->setFrontier('forum', 10);
        self::assertSame(50, $this->out->getFrontier('forum'));

        $this->out->resetFrontier('forum');
        self::assertSame(0, $this->out->getFrontier('forum'));
    }

    /**
     * Tests ensureFulltextIndex() is a no-op when the index already exists.
     */
    public function testEnsureFulltextIndexIsNoOpWhenPresent()
    {
        $this->out->ensureFulltextIndex();

        self::assertTrue($this->out->fulltextIndexExists());
    }

    /**
     * Tests the drop/ensure round trip of the FULLTEXT index.
     *
     * Ends with the index present: the capability flags are cached statically
     * for the whole test process and must stay consistent for other classes.
     */
    public function testDropAndEnsureFulltextIndexRoundTrip()
    {
        self::assertTrue($this->out->fulltextIndexExists());

        $this->out->dropFulltextIndex();
        self::assertFalse($this->out->fulltextIndexExists());

        $this->out->ensureFulltextIndex();
        self::assertTrue($this->out->fulltextIndexExists());
    }

    /**
     * Tests the version parsing and FULLTEXT capability detection.
     */
    public function testServerVersionAndFulltextSupported()
    {
        self::assertMatchesRegularExpression('/^\d+\.\d+/', $this->out->serverVersion());
        self::assertTrue($this->out->fulltextSupported());
        self::assertTrue($this->out->fulltextAvailable());
    }
}
