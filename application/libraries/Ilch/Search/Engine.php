<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

use Ilch\Database\Mysql;
use Ilch\Registry;

/**
 * Search engine over the shared [prefix]_search_documents table.
 *
 * Two modes, selected at runtime (never persisted):
 * - 'fulltext': InnoDB FULLTEXT (MySQL 5.6+ / MariaDB) when the index exists
 *   and at least one query token is long enough for the InnoDB tokenizer.
 * - 'like':     LIKE fallback (MySQL 5.5, or all-short-token queries); a
 *   bounded fetch window (500 rows) ranked in PHP.
 *
 * A FULLTEXT result is re-served through the LIKE path when the query
 * throws (broken FTS machinery, e.g. after a crash) or returns zero rows
 * (all-stopword queries, custom stopword table, stale index), so recall
 * degrades gracefully instead of erroring or returning nothing.
 *
 * One shared access predicate serves both modules:
 * access_groups = '' ⇒ admin-only; non-admins see a document when one of
 * their groups (plus the guest group 3) appears in the CSV; admins bypass
 * the filter entirely.
 */
class Engine
{
    /** Bounded fetch window for the LIKE fallback (phase-3: tunable). */
    private const LIKE_FETCH_LIMIT = 500;

    private Mysql $db;

    /** Cached per request (static): runtime capability detection only. */
    private static ?int $minTokenSize     = null;
    private static bool $schemaOpQueued   = false;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    // ------------------------------------------------------------------
    // Runtime capability detection (statically cached, never persisted)
    // ------------------------------------------------------------------

    /**
     * @return string e.g. "5.5.5", "5.7.30", "10.11.2-MariaDB"
     */
    public function serverVersion(): string
    {
        return (new Store($this->db))->serverVersion();
    }

    /**
     * @return bool InnoDB FULLTEXT is supported (MariaDB or MySQL 5.6+)
     */
    public function fulltextSupported(): bool
    {
        return (new Store($this->db))->fulltextSupported();
    }

    /**
     * @return bool the ft_title_body index exists on the documents table
     */
    public function fulltextIndexExists(): bool
    {
        return (new Store($this->db))->fulltextIndexExists();
    }

    /**
     * @return bool FULLTEXT searches can be served right now
     */
    public function fulltextAvailable(): bool
    {
        return (new Store($this->db))->fulltextAvailable();
    }

    /**
     * @return int @@innodb_ft_min_token_size (default 3)
     */
    public function minTokenSize(): int
    {
        if (self::$minTokenSize === null) {
            $value = $this->db->queryCell('SELECT @@innodb_ft_min_token_size');
            self::$minTokenSize = max(1, (int)$value);
        }
        return self::$minTokenSize;
    }

    // ------------------------------------------------------------------
    // Public API
    // ------------------------------------------------------------------

    /**
     * @param string $query raw query string (trimmed, max 100 chars enforced by caller)
     * @param array $filters {module?: string, locale?: string}
     * @param array $user {isAdmin: bool, groups: int[]} (groups always include 3)
     * @param int $page
     * @param int $pageSize
     * @return array {hits: SearchHit[], total: int, approximate: bool, engine: 'fulltext'|'like'}
     */
    public function search(
        string $query,
        array $filters = [],
        array $user = [],
        int $page = 1,
        int $pageSize = 20
    ): array {
        $empty = ['hits' => [], 'total' => 0, 'approximate' => false, 'engine' => 'like'];

        $query = trim((string)$query);
        if ($query === '') {
            return $empty;
        }

        $store = new Store($this->db);
        if (!$store->exists()) {
            // Search module not installed yet: nothing to search in.
            return $empty;
        }

        // Supported but not built yet: queue the one-off ALTER once per
        // request (processed only by the search.drain task), then continue
        // with LIKE. Covers upgrades 5.5 → 5.7 under an existing install.
        if ($store->fulltextSupported() && !$store->fulltextIndexExists() && !self::$schemaOpQueued) {
            self::$schemaOpQueued = true;
            Queue::enqueue('search', Queue::OP_ENSURE_SCHEMA);
        }

        $tokens = preg_split('/[^\p{L}\p{N}_]+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($tokens) || $tokens === []) {
            return $empty;
        }
        $tokens = array_values(array_unique(array_map('strval', $tokens)));

        $filters = [
            'module' => isset($filters['module']) && $filters['module'] !== '' ? (string)$filters['module'] : '',
            'locale' => isset($filters['locale']) && $filters['locale'] !== '' ? (string)$filters['locale'] : '',
        ];
        $predicate = $this->accessPredicate($user);
        $conditions = $predicate[0] . $this->filterConditions($filters);

        $mode = 'like';
        if ($store->fulltextAvailable() && $this->hasEligibleToken($tokens)) {
            $mode = 'fulltext';
        }

        if ($mode !== 'fulltext') {
            return $this->searchLike($tokens, $conditions, $page, $pageSize);
        }

        try {
            $result = $this->searchFulltext($tokens, $conditions, $page, $pageSize);
        } catch (\Exception) {
            // FTS machinery failed at query time (e.g. stale index after a
            // crash); degrade to the LIKE path.
            $result = $this->searchLike($tokens, $conditions, $page, $pageSize);
        }

        // Zero FULLTEXT hits can mean stopwords, a custom stopword table or
        // a stale FTS index; re-serve via LIKE so recall stays correct.
        if ($result['total'] === 0) {
            $result = $this->searchLike($tokens, $conditions, $page, $pageSize);
        }

        return $result;
    }

    /**
     * Shared visibility predicate (see class docblock).
     *
     * @param array $user {isAdmin: bool, groups: int[]}
     * @return array [sql, params] fragment referencing alias d; params are
     *   the inlined group ints (documented, since Mysql has no bindings)
     */
    public function accessPredicate(array $user): array
    {
        if (!empty($user['isAdmin'])) {
            return ['1 = 1', []];
        }
        $groups = array_map('intval', $user['groups'] ?? []);
        $groups[] = 3; // guest group always applies
        $groups = array_values(array_unique($groups));

        $parts  = [];
        $params = [];
        foreach ($groups as $i => $g) {
            $parts[]  = 'FIND_IN_SET(' . (int)$g . ', d.access_groups)';
            $params['ag' . $i] = (int)$g;
        }
        return ['(' . implode(' OR ', $parts) . ')', $params];
    }

    // ------------------------------------------------------------------
    // Internals
    // ------------------------------------------------------------------

    /**
     * At least one token long enough for the InnoDB tokenizer. All-short
     * token queries fall back to LIKE, because InnoDB would silently ignore
     * every token and return zero rows.
     *
     * @param array $tokens
     * @return bool
     */
    private function hasEligibleToken(array $tokens): bool
    {
        $min = $this->minTokenSize();
        foreach ($tokens as $token) {
            if (mb_strlen($token) >= $min) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $filters {module, locale}
     * @return string additional AND conditions (empty string when none)
     */
    private function filterConditions(array $filters): string
    {
        $sql = '';
        if ($filters['module'] !== '') {
            $sql .= ' AND d.module = ' . $this->db->escape($filters['module'], true);
        }
        if ($filters['locale'] !== '') {
            $sql .= ' AND d.locale = ' . $this->db->escape($filters['locale'], true);
        }
        return $sql;
    }

    /**
     * FULLTEXT mode: NL-mode semantics (OR recall of any token, no prefix
     * matching). Exact total via COUNT(*); the page is fetched with
     * LIMIT/OFFSET ordered by raw score, then post-ranked in PHP:
     * final = score + 1.0 * (distinct tokens found in the title). The boost
     * only reorders rows within the fetched page (bounded perturbation).
     *
     * @param array $tokens
     * @param string $conditions access predicate + module/locale filters
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    private function searchFulltext(array $tokens, string $conditions, int $page, int $pageSize): array
    {
        $match = 'MATCH(d.title, d.body) AGAINST (' . $this->db->escape(implode(' ', $tokens), true) . ' IN NATURAL LANGUAGE MODE)';
        $where = $match . ' AND ' . $conditions;

        $total = (int)$this->db->queryCell('SELECT COUNT(*) FROM [prefix]_search_documents d WHERE ' . $where);

        $offset = (max(1, $page) - 1) * $pageSize;
        $rows = $this->db->queryArray(
            'SELECT d.*, ' . $match . ' AS score FROM [prefix]_search_documents d'
            . ' WHERE ' . $where
            . ' ORDER BY score DESC, d.date_activity DESC, d.id DESC'
            . ' LIMIT ' . (int)$pageSize . ' OFFSET ' . (int)$offset
        );

        $hits = [];
        foreach ($rows as $row) {
            $title = mb_strtolower((string)$row['title']);
            $distinctTitleHits = 0;
            foreach ($tokens as $token) {
                if (mb_strpos($title, mb_strtolower($token)) !== false) {
                    $distinctTitleHits++;
                }
            }
            $hits[] = $this->toHit($row, (float)$row['score'] + 1.0 * $distinctTitleHits);
        }

        $hits = $this->sortHits($hits);

        return ['hits' => $hits, 'total' => $total, 'approximate' => false, 'engine' => 'fulltext'];
    }

    /**
     * LIKE fallback (MySQL 5.5 / all-short-token queries).
     *
     * Fetches a bounded window (LIKE_FETCH_LIMIT rows) ordered by title
     * match first, ranks it in PHP:
     * score = 10 * distinctTitleHits + min(totalBodyHits, 10) + recency(0..1)
     * then slices the requested page. total = number of fetched rows,
     * approximate = true (ranking is only correct within the fetch window).
     *
     * @param array $tokens
     * @param string $conditions access predicate + module/locale filters
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    private function searchLike(array $tokens, string $conditions, int $page, int $pageSize): array
    {
        $likeParts  = [];
        $titleParts = [];
        foreach ($tokens as $token) {
            $like = $this->db->escape('%' . $token . '%', true);
            $likeParts[]  = 'd.title LIKE ' . $like;
            $likeParts[]  = 'd.body LIKE ' . $like;
            $titleParts[] = 'd.title LIKE ' . $like;
        }
        $where = '(' . implode(' OR ', $likeParts) . ') AND ' . $conditions;

        $rows = $this->db->queryArray(
            'SELECT d.* FROM [prefix]_search_documents d'
            . ' WHERE ' . $where
            . ' ORDER BY (' . implode(' OR ', $titleParts) . ') DESC, d.date_activity DESC, d.id DESC'
            . ' LIMIT ' . self::LIKE_FETCH_LIMIT
        );

        $now = time();
        $hits = [];
        foreach ($rows as $row) {
            $title = mb_strtolower((string)$row['title']);
            $body  = mb_strtolower((string)$row['body']);

            $distinctTitleHits = 0;
            $totalBodyHits = 0;
            foreach ($tokens as $token) {
                $tokenLower = mb_strtolower($token);
                if (mb_strpos($title, $tokenLower) !== false) {
                    $distinctTitleHits++;
                }
                // UTF-8 is self-synchronizing: byte-substring counts equal
                // character occurrence counts.
                $totalBodyHits += substr_count($body, $tokenLower);
            }

            $recency = 0.0;
            if (!empty($row['date_activity'])) {
                $ts = strtotime((string)$row['date_activity']);
                if ($ts !== false) {
                    // Linear decay over one year (0..1).
                    $recency = max(0.0, 1.0 - ($now - $ts) / (365 * 24 * 3600));
                }
            }

            $score = 10.0 * $distinctTitleHits + min($totalBodyHits, 10) + $recency;
            $hits[] = $this->toHit($row, $score);
        }

        $hits = $this->sortHits($hits);
        $offset = (max(1, $page) - 1) * $pageSize;
        $hits = array_slice($hits, $offset, $pageSize);

        return [
            'hits' => $hits,
            'total' => count($rows),
            'approximate' => true,
            'engine' => 'like',
        ];
    }

    /**
     * @param SearchHit[] $hits
     * @return SearchHit[] sorted by score DESC, date_activity DESC, docId DESC
     *   (usort is stable on PHP 8+)
     */
    private function sortHits(array $hits): array
    {
        usort($hits, function (SearchHit $a, SearchHit $b): int {
            if ($a->score !== $b->score) {
                return $b->score <=> $a->score;
            }
            $da = $a->dateActivity ?? '';
            $db = $b->dateActivity ?? '';
            if ($da !== $db) {
                return $db <=> $da;
            }
            return $b->docId <=> $a->docId;
        });
        return $hits;
    }

    /**
     * @param array $row raw documents row
     * @param float $score
     * @return SearchHit
     */
    private function toHit(array $row, float $score): SearchHit
    {
        $meta = [];
        if (!empty($row['meta'])) {
            $decoded = json_decode((string)$row['meta'], true);
            if (is_array($decoded)) {
                $meta = $decoded;
            }
        }

        return new SearchHit(
            (string)$row['module'],
            (string)$row['doc_type'],
            (int)$row['doc_id'],
            (string)$row['locale'],
            $row['parent_id'] === null ? null : (int)$row['parent_id'],
            (string)$row['title'],
            (string)$row['body'],
            $score,
            $row['date_activity'] === null ? null : (string)$row['date_activity'],
            $meta
        );
    }
}
