<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

use Ilch\Database\Mysql;
use Ilch\Registry;

/**
 * Access to the shared [prefix]_search_documents table and the
 * [prefix]_search_progress frontier table.
 *
 * Capability checks (server version, FULLTEXT support, index existence) are
 * runtime detections, statically cached per request — never persisted, so a
 * MySQL 5.5 → 5.7 server upgrade self-heals on the next request.
 */
class Store
{
    private const DOCUMENTS_TABLE = '[prefix]_search_documents';
    private const PROGRESS_TABLE  = '[prefix]_search_progress';
    private const FULLTEXT_INDEX  = 'ft_title_body';

    private Mysql $db;

    /** Cached per request. */
    private static ?string $serverVersion       = null;
    private static ?bool $fulltextSupported   = null;
    private static ?bool $fulltextIndexExists = null;
    private static ?bool $documentsExist      = null;
    private static ?bool $progressExist       = null;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    /**
     * @return bool true when the documents table exists (cached per request)
     */
    public function exists(): bool
    {
        if (self::$documentsExist === null) {
            self::$documentsExist = $this->db->ifTableExists(self::DOCUMENTS_TABLE);
        }
        return self::$documentsExist;
    }

    /**
     * Insert-or-update one document row (ON DUPLICATE KEY UPDATE on uniq_doc).
     *
     * @param array $doc keys: module, docType, docId, locale, parentId, scopeKey,
     *   scopeId, title, body, accessGroups, dateActivity ('Y-m-d H:i:s' or null),
     *   meta (JSON string or null), contentHash (md5, optional)
     * @return void
     */
    public function upsert(array $doc): void
    {
        $db = $this->db;
        $values = [
            'module'        => $db->escape((string)($doc['module'] ?? ''), true),
            'doc_type'      => $db->escape((string)($doc['docType'] ?? ''), true),
            'doc_id'        => (int)($doc['docId'] ?? 0),
            'locale'        => $db->escape((string)($doc['locale'] ?? ''), true),
            'parent_id'     => isset($doc['parentId']) && $doc['parentId'] !== null ? (int)$doc['parentId'] : 'NULL',
            'scope_key'     => $db->escape((string)($doc['scopeKey'] ?? ''), true),
            'scope_id'      => isset($doc['scopeId']) && $doc['scopeId'] !== null ? (int)$doc['scopeId'] : 'NULL',
            'title'         => $db->escape((string)($doc['title'] ?? ''), true),
            'body'          => $db->escape((string)($doc['body'] ?? ''), true),
            'access_groups' => $db->escape((string)($doc['accessGroups'] ?? ''), true),
            'date_activity' => isset($doc['dateActivity']) && $doc['dateActivity'] !== null
                ? $db->escape((string)$doc['dateActivity'], true) : 'NULL',
            'meta'          => isset($doc['meta']) && $doc['meta'] !== null
                ? $db->escape((string)$doc['meta'], true) : 'NULL',
            'content_hash'  => $db->escape((string)($doc['contentHash'] ?? ''), true),
        ];

        $sql = 'INSERT INTO ' . self::DOCUMENTS_TABLE . ' (module, doc_type, doc_id, locale, parent_id, scope_key, scope_id, title, body, access_groups, date_activity, meta, content_hash) VALUES ('
            . implode(', ', $values) . ') ON DUPLICATE KEY UPDATE '
            . 'parent_id = VALUES(parent_id), '
            . 'scope_key = VALUES(scope_key), '
            . 'scope_id = VALUES(scope_id), '
            . 'title = VALUES(title), '
            . 'body = VALUES(body), '
            . 'access_groups = VALUES(access_groups), '
            . 'date_activity = VALUES(date_activity), '
            . 'meta = VALUES(meta), '
            . 'content_hash = VALUES(content_hash)';

        $this->db->query($sql);
    }

    /**
     * Delete the document row(s) for one source row.
     *
     * @param string $module
     * @param string $docType
     * @param int $docId
     * @param string $locale '' = all locales
     * @return int number of deleted rows
     */
    public function deleteDoc(string $module, string $docType, int $docId, string $locale = ''): int
    {
        $sql = 'DELETE FROM ' . self::DOCUMENTS_TABLE . ' WHERE module = ' . $this->db->escape($module, true)
            . ' AND doc_type = ' . $this->db->escape($docType, true)
            . ' AND doc_id = ' . (int)$docId;
        if ($locale !== '') {
            $sql .= ' AND locale = ' . $this->db->escape($locale, true);
        }
        $this->db->query($sql);
        return $this->db->getAffectedRows();
    }

    /**
     * @return int number of deleted rows
     */
    public function deleteByScope(string $module, string $scopeKey, int $scopeId): int
    {
        $sql = 'DELETE FROM ' . self::DOCUMENTS_TABLE . ' WHERE module = ' . $this->db->escape($module, true)
            . ' AND scope_key = ' . $this->db->escape($scopeKey, true)
            . ' AND scope_id = ' . (int)$scopeId;
        $this->db->query($sql);
        return $this->db->getAffectedRows();
    }

    /**
     * Delete all child documents (e.g. posts of a topic).
     *
     * @return int number of deleted rows
     */
    public function deleteByParent(string $module, string $docType, int $parentId): int
    {
        $sql = 'DELETE FROM ' . self::DOCUMENTS_TABLE . ' WHERE module = ' . $this->db->escape($module, true)
            . ' AND doc_type = ' . $this->db->escape($docType, true)
            . ' AND parent_id = ' . (int)$parentId;
        $this->db->query($sql);
        return $this->db->getAffectedRows();
    }

    /**
     * @return int number of deleted rows
     */
    public function deleteByModule(string $module): int
    {
        $sql = 'DELETE FROM ' . self::DOCUMENTS_TABLE . ' WHERE module = ' . $this->db->escape($module, true);
        $this->db->query($sql);
        return $this->db->getAffectedRows();
    }

    /**
     * Document counts per module (for the admin overview).
     *
     * @return array<string, int> module => document count
     */
    public function countDocumentsByModule(): array
    {
        if (!$this->exists()) {
            return [];
        }
        $rows = $this->db->queryArray(
            'SELECT module, COUNT(*) AS cnt FROM ' . self::DOCUMENTS_TABLE . ' GROUP BY module'
        );
        $counts = [];
        foreach ($rows as $row) {
            $counts[(string)$row['module']] = (int)$row['cnt'];
        }
        return $counts;
    }

    /**
     * Rows for the given (module, docType) ids — used to resolve topic titles
     * at query time so title edits are never stale.
     *
     * @param string $module
     * @param string $docType
     * @param array $docIds
     * @return array<int, array> rows keyed by doc_id
     */
    public function getDocsByIds(string $module, string $docType, array $docIds): array
    {
        $ids = array_values(array_unique(array_map('intval', $docIds)));
        if ($ids === []) {
            return [];
        }
        $sql = 'SELECT * FROM ' . self::DOCUMENTS_TABLE . ' WHERE module = ' . $this->db->escape($module, true)
            . ' AND doc_type = ' . $this->db->escape($docType, true)
            . ' AND doc_id IN (' . implode(', ', $ids) . ')';
        $rows = $this->db->queryArray($sql);
        $byId = [];
        foreach ($rows as $row) {
            $byId[(int)$row['doc_id']] = $row;
        }
        return $byId;
    }

    /**
     * Server version string, e.g. "5.5.5", "5.7.30", "10.11.2-MariaDB".
     *
     * @return string
     */
    public function serverVersion(): string
    {
        if (self::$serverVersion === null) {
            self::$serverVersion = (string)$this->db->queryCell('SELECT VERSION()');
        }
        return self::$serverVersion;
    }

    /**
     * InnoDB FULLTEXT is supported on MariaDB (all versions) and on MySQL
     * 5.6 and newer.
     *
     * @return bool
     */
    public function fulltextSupported(): bool
    {
        if (self::$fulltextSupported === null) {
            $version = $this->serverVersion();
            if (stripos($version, 'mariadb') !== false || stripos($version, 'maria') !== false) {
                self::$fulltextSupported = true;
            } elseif (preg_match('/^(\d+)\.(\d+)/', $version, $m) === 1) {
                $major = (int)$m[1];
                $minor = (int)$m[2];
                self::$fulltextSupported = $major > 5 || ($major === 5 && $minor >= 6);
            } else {
                self::$fulltextSupported = false;
            }
        }
        return self::$fulltextSupported;
    }

    /**
     * Whether the ft_title_body FULLTEXT index exists on the documents table.
     *
     * @return bool
     */
    public function fulltextIndexExists(): bool
    {
        if (self::$fulltextIndexExists === null) {
            $table = $this->db->getSqlWithPrefix(self::DOCUMENTS_TABLE);
            $row = $this->db->queryRow(
                'SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE()'
                . ' AND table_name = ' . $this->db->escape($table, true)
                . ' AND index_name = ' . $this->db->escape(self::FULLTEXT_INDEX, true)
                . " AND index_type = 'FULLTEXT' LIMIT 1"
            );
            self::$fulltextIndexExists = $row !== null;
        }
        return self::$fulltextIndexExists;
    }

    /**
     * @return bool true when FULLTEXT searches can be served right now
     */
    public function fulltextAvailable(): bool
    {
        return $this->fulltextSupported() && $this->fulltextIndexExists();
    }

    /**
     * Idempotent, version-aware: when the server supports InnoDB FULLTEXT and
     * the index is missing, run the guarded ALTER. No-op otherwise
     * (incl. MySQL 5.5).
     *
     * InnoDB DDL is atomic: a failed or interrupted ALTER never leaves a
     * partial index behind.
     *
     * @return void
     */
    public function ensureFulltextIndex(): void
    {
        if (!$this->exists() || !$this->fulltextSupported() || $this->fulltextIndexExists()) {
            return;
        }
        $this->db->query('ALTER TABLE ' . self::DOCUMENTS_TABLE . ' ADD FULLTEXT KEY ' . self::FULLTEXT_INDEX . ' (title, body)');
        self::$fulltextIndexExists = true;
    }

    /**
     * Idempotent: drop ft_title_body when present. No-op otherwise. Used by the
     * importer's final search step for the index-last bulk build (the index is
     * then rebuilt one shot via ensureFulltextIndex()).
     *
     * @return void
     */
    public function dropFulltextIndex(): void
    {
        if (!$this->exists() || !$this->fulltextIndexExists()) {
            return;
        }
        $this->db->query('ALTER TABLE ' . self::DOCUMENTS_TABLE . ' DROP INDEX ' . self::FULLTEXT_INDEX);
        self::$fulltextIndexExists = false;
    }

    /**
     * @param string $module
     * @return int current frontier (0 when no row)
     */
    public function getFrontier(string $module): int
    {
        if (self::$progressExist === null) {
            self::$progressExist = $this->db->ifTableExists(self::PROGRESS_TABLE);
        }
        if (!self::$progressExist) {
            return 0;
        }
        $row = $this->db->queryRow(
            'SELECT frontier FROM ' . self::PROGRESS_TABLE . ' WHERE module = ' . $this->db->escape($module, true) . ' LIMIT 1'
        );
        return $row === null ? 0 : (int)$row['frontier'];
    }

    /**
     * Persist the frontier (GREATEST() upsert — never moves backwards).
     *
     * @param string $module
     * @param int $frontier
     * @return void
     */
    public function setFrontier(string $module, int $frontier): void
    {
        if (self::$progressExist === null) {
            self::$progressExist = $this->db->ifTableExists(self::PROGRESS_TABLE);
        }
        if (!self::$progressExist) {
            return;
        }
        $this->db->query(
            'INSERT INTO ' . self::PROGRESS_TABLE . ' (module, frontier, updated) VALUES ('
            . $this->db->escape($module, true) . ', ' . (int)$frontier . ', NOW())'
            . ' ON DUPLICATE KEY UPDATE frontier = GREATEST(frontier, VALUES(frontier)), updated = NOW()'
        );
    }

    /**
     * Drop the persisted backfill frontier for one module so the
     * search.backfill task rebuilds it from zero. Used by the admin
     * "reindex" action.
     *
     * @param string $module
     * @return void
     */
    public function resetFrontier(string $module): void
    {
        if (self::$progressExist === null) {
            self::$progressExist = $this->db->ifTableExists(self::PROGRESS_TABLE);
        }
        if (!self::$progressExist) {
            return;
        }
        $this->db->query('DELETE FROM ' . self::PROGRESS_TABLE . ' WHERE module = ' . $this->db->escape($module, true));
    }
}
