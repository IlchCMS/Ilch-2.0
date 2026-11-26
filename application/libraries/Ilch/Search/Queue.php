<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

use Ilch\Database\Mysql;
use Ilch\Registry;
use Ilch\Scheduler\Scheduler;
use Modules\Admin\Mappers\Logs as LogsMapper;

/**
 * Work queue between the hooked source modules and the search index.
 *
 * Writing modules never touch the documents table directly: they call the
 * static enqueue(), which records one row in [prefix]_search_queue and
 * attempts an inline flush so the change is searchable almost immediately.
 * The search.drain scheduler task sweeps whatever the inline flush missed
 * (failed ops, busy requests).
 *
 * enqueue() never throws: search must never break a writing module.
 */
class Queue
{
    public const OP_INDEX         = 1;
    public const OP_DELETE        = 2;
    public const OP_DELETE_SCOPE  = 3;
    public const OP_REINDEX_SCOPE = 4;
    public const OP_ENSURE_SCHEMA = 5;

    private const TABLE = '[prefix]_search_queue';

    /**
     * The logs table has no system user; the queue writes with the admin
     * user id (1) as the system identity.
     */
    private const LOG_USER_ID = 1;

    private Mysql $db;

    /**
     * Import mode (per-request static, off by default): while set, enqueue()
     * is a pure no-op (no queue row, no flush). Set by ilch1importer on every
     * import request so bulk imports run at full speed. The flag dies with
     * the request, so a crashed import can never leave search disabled on
     * later requests.
     */
    private static bool $importMode = false;

    /** Cached per request: both search tables exist. */
    private static ?bool $tablesExist = null;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    /**
     * @param bool $on
     * @return void
     */
    public static function setImportMode(bool $on = true): void
    {
        self::$importMode = $on;
    }

    /**
     * Enqueue one operation (INSERT IGNORE against the unique key, so
     * duplicate enqueues coalesce), then attempt an inline flush so the
     * write is searchable almost immediately.
     *
     * A null docId/scopeId is stored as 0: MySQL unique indexes treat NULLs
     * as distinct values, which would silently defeat the coalescing.
     *
     * No-op while import mode is set or when the search tables do not exist
     * (fresh-install ordering). Never throws.
     *
     * @param string $module
     * @param int $op OP_* constant
     * @param string $docType
     * @param int|null $docId
     * @param string $locale
     * @param string $scopeKey
     * @param int|null $scopeId
     * @return void
     */
    public static function enqueue(
        string $module,
        int $op,
        string $docType = '',
        ?int $docId = null,
        string $locale = '',
        string $scopeKey = '',
        ?int $scopeId = null
    ): void {
        try {
            if (self::$importMode) {
                return;
            }
            $queue = new self();
            if (!$queue->hasTables()) {
                return;
            }
            $queue->db->query(
                'INSERT IGNORE INTO ' . self::TABLE . ' (module, op, doc_type, doc_id, locale, scope_key, scope_id, created) VALUES ('
                . $queue->db->escape($module, true) . ', ' . (int)$op . ', '
                . $queue->db->escape($docType, true) . ', ' . (int)($docId ?? 0) . ', '
                . $queue->db->escape($locale, true) . ', '
                . $queue->db->escape($scopeKey, true) . ', ' . (int)($scopeId ?? 0) . ', '
                . 'NOW())'
            );
            $queue->flush(10, 250);
        } catch (\Throwable $e) {
            // Search must never break a writing module.
            self::logFailure('Ilch Search queue enqueue failed: ' . $e->getMessage());
        }
    }

    /**
     * @return bool true when both search tables exist; cached per request
     */
    public function hasTables(): bool
    {
        if (self::$tablesExist === null) {
            self::$tablesExist = $this->db->ifTableExists(self::TABLE)
                && $this->db->ifTableExists('[prefix]_search_documents');
        }
        return self::$tablesExist;
    }

    /**
     * Number of ops waiting in the queue (for the admin overview).
     *
     * @return int
     */
    public function count(): int
    {
        if (!$this->hasTables()) {
            return 0;
        }
        return (int)$this->db->queryCell('SELECT COUNT(*) FROM ' . self::TABLE);
    }

    /**
     * Process queued ops oldest-first: dispatch via Indexers::get($module),
     * delete the processed rows in one DELETE ... WHERE id IN (...).
     *
     * Stops (rows stay queued) on: time limit, scheduler budget exhausted
     * (timeLeftMs() <= 100), or the first failing op (the error goes to the
     * admin logs with module/op/ids, \error_log() as fallback; the scheduler
     * task's retry picks the queue up later).
     *
     * OP_ENSURE_SCHEMA is processed directly (no indexer) and only when a
     * Scheduler instance is passed — never by the inline flush, so a page
     * write is never blocked by an ALTER.
     *
     * Unknown module (no indexer) for ops 1–4: the row is deleted — nothing
     * can ever index it, so it would be a permanent poison row.
     *
     * @param int $maxRows max rows per loop
     * @param int $timeLimitMs hard wall-clock cap for this flush call
     * @param Scheduler|null $scheduler
     * @return int number of processed ops
     */
    public function flush(int $maxRows = 10, int $timeLimitMs = 250, ?Scheduler $scheduler = null): int
    {
        if (!$this->hasTables()) {
            return 0;
        }

        $processed = 0;
        $done = [];
        $start = microtime(true);

        while (count($done) < $maxRows) {
            // Budget checks before touching the next row.
            if ((microtime(true) - $start) * 1000.0 >= $timeLimitMs) {
                break;
            }
            if ($scheduler !== null && $scheduler->timeLeftMs() <= 100) {
                break;
            }

            // The batched DELETE below only runs after the loop, so already
            // processed rows must be excluded here — otherwise the same first
            // row would be re-selected (and re-dispatched) up to $maxRows
            // times and every later row would be starved.
            $where = $done === [] ? '' : ' WHERE id NOT IN (' . implode(', ', $done) . ')';
            $row = $this->db->queryRow(
                'SELECT * FROM ' . self::TABLE . $where . ' ORDER BY id ASC LIMIT 1'
            );
            if ($row === null) {
                break;
            }

            $id     = (int)$row['id'];
            $module = (string)$row['module'];
            $op     = (int)$row['op'];

            if ($op === self::OP_ENSURE_SCHEMA) {
                if ($scheduler === null) {
                    // Inline flush must never run the ALTER; leave the row
                    // queued for the search.drain task.
                    break;
                }
                try {
                    (new Store($this->db))->ensureFulltextIndex();
                } catch (\Throwable $e) {
                    self::logFailure('Ilch Search queue: OP_ENSURE_SCHEMA failed: ' . $e->getMessage());
                    break;
                }
                $done[] = $id;
                $processed++;
                continue;
            }

            $indexer = Indexers::get($module);
            if ($indexer === null) {
                // Unknown module: discard the row (poison-row prevention).
                $done[] = $id;
                $processed++;
                continue;
            }

            try {
                switch ($op) {
                    case self::OP_INDEX:
                        $indexer->indexDoc((string)$row['doc_type'], (int)$row['doc_id'], (string)$row['locale']);
                        break;
                    case self::OP_DELETE:
                        $indexer->deleteDoc((string)$row['doc_type'], (int)$row['doc_id'], (string)$row['locale']);
                        break;
                    case self::OP_DELETE_SCOPE:
                        $indexer->deleteScope((string)$row['scope_key'], (int)$row['scope_id']);
                        break;
                    case self::OP_REINDEX_SCOPE:
                        $indexer->reindexScope((string)$row['scope_key'], (int)$row['scope_id']);
                        break;
                    default:
                        // Unknown op: discard so it cannot poison the queue.
                        break;
                }
                $done[] = $id;
                $processed++;
            } catch (\Throwable $e) {
                self::logFailure('Ilch Search queue: op ' . $op . ' failed for module=' . $module
                    . ' doc_type=' . $row['doc_type'] . ' doc_id=' . $row['doc_id']
                    . ' locale=' . $row['locale'] . ' scope=' . $row['scope_key'] . '/' . $row['scope_id']
                    . ': ' . $e->getMessage());
                // Row stays queued; the scheduler retry picks it up later.
                break;
            }
        }

        if ($done !== []) {
            $this->db->query('DELETE FROM ' . self::TABLE . ' WHERE id IN (' . implode(', ', $done) . ')');
        }

        return $processed;
    }

    /**
     * Write a failure message to the admin logs (admin center > Infos >
     * Logs). If the log write itself fails (e.g. the
     * database is down), the message still goes to \error_log().
     *
     * The mapper deduplicates identical messages per user per minute, so a
     * persistently failing op cannot spam the admin logs.
     *
     * @param string $message
     * @return void
     */
    private static function logFailure(string $message): void
    {
        $message = self::sanitizeLog($message);
        try {
            (new LogsMapper())->saveLog(self::LOG_USER_ID, $message);
        } catch (\Throwable) {
            \error_log($message);
        }
    }

    /**
     * Strip control characters and cap the message so it fits the logs
     * table's info column (VARCHAR(255)); saveLog() does not truncate.
     *
     * @param string $message
     * @return string
     */
    private static function sanitizeLog(string $message): string
    {
        $message = preg_replace('/[\x00-\x1F\x7F]/', ' ', $message);
        return function_exists('mb_substr') ? mb_substr($message, 0, 255) : substr($message, 0, 255);
    }
}
