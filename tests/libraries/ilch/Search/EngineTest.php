<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Search\Config\Config as SearchModuleConfig;

class EngineTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/_files/search_documents.yml'];

    protected Engine $out;
    protected PhpunitDataset $phpunitDataset;

    protected function setUp(): void
    {
        parent::setUp();

        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        // InnoDB only exposes FULLTEXT index entries after commit, so the
        // fixture rows have to be committed to test the FULLTEXT path.
        // The test's own writes are still rolled back by tearDown(), and the
        // next test's table restore (DROP + CREATE, before START TRANSACTION)
        // clears the committed fixture state. Raw COMMIT: the parent started
        // the transaction with a raw "START TRANSACTION", so the Mysql
        // transaction level (and thus commit()) is not aware of it.
        $this->db->query('COMMIT');

        $this->out = new Engine($this->db);
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
     * Fixture assumes the default @@innodb_ft_min_token_size (3).
     */
    private function requireDefaultMinTokenSize(): void
    {
        if ($this->out->minTokenSize() !== 3) {
            self::markTestSkipped('Fixture assumes the default @@innodb_ft_min_token_size (3).');
        }
    }

    /**
     * Extracts the doc ids of a result set in hit order.
     *
     * @param array $result
     * @return array<int>
     */
    private function docIds(array $result): array
    {
        return array_map(static fn (SearchHit $hit): int => $hit->docId, $result['hits']);
    }

    /**
     * Asserts that a result set contains exactly the given doc ids (any order).
     *
     * @param array $result
     * @param array $expected
     */
    private function assertDocIdSet(array $result, array $expected): void
    {
        $actual = $this->docIds($result);
        sort($actual);
        sort($expected);
        self::assertSame($expected, $actual);
    }

    // ------------------------------------------------------------------
    // Basics / guards
    // ------------------------------------------------------------------

    /**
     * Tests that an empty or whitespace-only query returns an empty result.
     *
     * @param string $query
     * @dataProvider emptyQueryProvider
     */
    public function testEmptyQueryReturnsEmptyResult(string $query)
    {
        $result = $this->out->search($query, [], ['isAdmin' => true]);

        self::assertSame([], $result['hits']);
        self::assertSame(0, $result['total']);
        self::assertFalse($result['approximate']);
        self::assertSame('like', $result['engine']);
    }

    public static function emptyQueryProvider(): array
    {
        return [
            'empty string' => [''],
            'whitespace only' => ['   '],
        ];
    }

    /**
     * Tests that a FULLTEXT query serves the full hit set with exact total.
     */
    public function testFulltextHitSet()
    {
        $result = $this->out->search('zebra', [], ['isAdmin' => true]);

        self::assertSame('fulltext', $result['engine']);
        self::assertFalse($result['approximate']);
        self::assertSame(6, $result['total']);
        self::assertDocIdSet($result, [101, 201, 204, 301, 302, 208]);
    }

    /**
     * Tests that the title boost keeps the body-only hit last.
     */
    public function testFulltextTitleBoostRanksBodyOnlyLast()
    {
        $result = $this->out->search('zebra', [], ['isAdmin' => true]);
        $hits = $result['hits'];

        self::assertCount(6, $hits);
        self::assertSame(208, $hits[count($hits) - 1]->docId);
    }

    /**
     * Tests FULLTEXT pagination with a small page size.
     */
    public function testFulltextPagination()
    {
        $user = ['isAdmin' => true];

        $page1 = $this->out->search('zebra', [], $user, 1, 2);
        self::assertSame(2, count($page1['hits']));
        self::assertSame(6, $page1['total']);

        $page3 = $this->out->search('zebra', [], $user, 3, 2);
        self::assertSame(2, count($page3['hits']));

        $page4 = $this->out->search('zebra', [], $user, 4, 2);
        self::assertSame(0, count($page4['hits']));
        self::assertSame(6, $page4['total']);
    }

    // ------------------------------------------------------------------
    // Access predicate
    // ------------------------------------------------------------------

    /**
     * Tests that admins get an unrestricted predicate.
     */
    public function testAccessPredicateAdminIsUnrestricted()
    {
        self::assertSame(['1 = 1', []], $this->out->accessPredicate(['isAdmin' => true]));
    }

    /**
     * Tests that the guest group is always added to the user groups.
     */
    public function testAccessPredicateAddsGuestGroup()
    {
        [$sql, $params] = $this->out->accessPredicate(['groups' => [2]]);

        self::assertSame('(FIND_IN_SET(2, d.access_groups) OR FIND_IN_SET(3, d.access_groups))', $sql);
        self::assertSame(['ag0' => 2, 'ag1' => 3], $params);
    }

    /**
     * Tests that admin-only documents (empty access_groups) stay hidden from guests.
     */
    public function testAdminOnlyDocHiddenFromGuests()
    {
        $result = $this->out->search('zebra', [], ['groups' => [3]]);

        self::assertDocIdSet($result, [301]);
        self::assertSame(1, $result['total']);
    }

    /**
     * Tests that a group member sees own-group and guest documents.
     */
    public function testGroupMemberSeesOwnAndGuestDocs()
    {
        $result = $this->out->search('zebra', [], ['groups' => [1]]);

        self::assertDocIdSet($result, [201, 204, 301]);
        self::assertSame(3, $result['total']);
    }

    // ------------------------------------------------------------------
    // Filters
    // ------------------------------------------------------------------

    /**
     * Tests the module filter.
     */
    public function testModuleFilter()
    {
        $user = ['isAdmin' => true];

        $forum = $this->out->search('zebra', ['module' => 'forum'], $user);
        self::assertDocIdSet($forum, [101, 201, 204, 208]);

        $article = $this->out->search('zebra', ['module' => 'article'], $user);
        self::assertDocIdSet($article, [301, 302]);
    }

    /**
     * Tests the locale filter.
     */
    public function testLocaleFilter()
    {
        $result = $this->out->search('zebra', ['locale' => 'de'], ['isAdmin' => true]);

        self::assertDocIdSet($result, [301]);
        self::assertSame(1, $result['total']);
    }

    /**
     * Tests the activity date range filter (FULLTEXT path).
     */
    public function testDateRangeFilter()
    {
        $user = ['isAdmin' => true];

        // April to June 2025: 301 (04-01), 302 (05-01), 204 (06-01).
        $mid = $this->out->search('zebra', ['from' => '2025-04-01', 'to' => '2025-06-30'], $user);
        self::assertDocIdSet($mid, [204, 301, 302]);
        self::assertSame(3, $mid['total']);

        // January 2025 only: 101, 201, 208 (all dated 2025-01-01).
        $jan = $this->out->search('zebra', ['from' => '2025-01-01', 'to' => '2025-01-31'], $user);
        self::assertDocIdSet($jan, [101, 201, 208]);
        self::assertSame(3, $jan['total']);

        // Invalid bounds are dropped silently (whitelist safety).
        $invalid = $this->out->search('zebra', ['from' => 'not-a-date', 'to' => '2025-99-99'], $user);
        self::assertDocIdSet($invalid, [101, 201, 204, 301, 302, 208]);
        self::assertSame(6, $invalid['total']);
    }

    /**
     * Tests that the LIKE path shares the date range WHERE clause.
     */
    public function testDateRangeAppliesToLikePath()
    {
        $this->requireDefaultMinTokenSize();

        // Of the 'ab' hits (203, 205, 206, 207) only 207 is dated 2025-06-01.
        $result = $this->out->search('ab', ['from' => '2025-06-01'], ['isAdmin' => true]);

        self::assertSame('like', $result['engine']);
        self::assertDocIdSet($result, [207]);
        self::assertSame(1, $result['total']);
    }

    /**
     * Tests the forum/category scope filter (scopeKey + scopeId).
     */
    public function testScopeFilter()
    {
        $user = ['isAdmin' => true];

        // Forum 11: topic 101, post 201, body doc 208.
        $forum = $this->out->search('zebra', ['scopeKey' => 'forum_id', 'scopeId' => 11], $user);
        self::assertDocIdSet($forum, [101, 201, 208]);
        self::assertSame(3, $forum['total']);

        // Category 21: both article locales.
        $cat = $this->out->search('zebra', ['scopeKey' => 'cat_id', 'scopeId' => 21], $user);
        self::assertDocIdSet($cat, [301, 302]);
        self::assertSame(2, $cat['total']);

        // A scope key without an id does not filter (both must be set).
        $noId = $this->out->search('zebra', ['scopeKey' => 'forum_id'], $user);
        self::assertDocIdSet($noId, [101, 201, 204, 301, 302, 208]);
        self::assertSame(6, $noId['total']);
    }

    // ------------------------------------------------------------------
    // LIKE path + degradation
    // ------------------------------------------------------------------

    /**
     * Tests that a short-token query is served by the LIKE engine.
     */
    public function testShortTokenUsesLikeEngine()
    {
        $this->requireDefaultMinTokenSize();

        $result = $this->out->search('ab', [], ['isAdmin' => true]);

        self::assertSame('like', $result['engine']);
        self::assertTrue($result['approximate']);
        self::assertSame(4, $result['total']);
        self::assertDocIdSet($result, [203, 205, 206, 207]);
    }

    /**
     * Tests the LIKE ranking: title hits first, then body hits, then recency.
     */
    public function testLikeRankingOrder()
    {
        $this->requireDefaultMinTokenSize();

        $result = $this->out->search('ab', [], ['isAdmin' => true]);

        // 205 title hit (10), 203 two body hits (2), 207 one body hit newer (1 + recency),
        // 206 one body hit older (1 + recency).
        self::assertSame([205, 203, 207, 206], $this->docIds($result));
    }

    /**
     * Tests that a stopword query degrades from FULLTEXT to LIKE.
     */
    public function testStopwordQueryDegradesToLike()
    {
        $result = $this->out->search('the', [], ['isAdmin' => true]);

        self::assertSame('like', $result['engine']);
        self::assertTrue($result['approximate']);
        self::assertDocIdSet($result, [102]);
        self::assertSame(1, $result['total']);
    }

    /**
     * Tests that a zero-hit query degrades to LIKE and stays empty.
     */
    public function testZeroHitQueryDegradesSilently()
    {
        $result = $this->out->search('xylophone', [], ['isAdmin' => true]);

        self::assertSame('like', $result['engine']);
        self::assertSame(0, $result['total']);
        self::assertSame([], $result['hits']);
    }
}
