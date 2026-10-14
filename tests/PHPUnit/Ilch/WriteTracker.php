<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

/**
 * Per-class bookkeeping of the tables test methods wrote to.
 *
 * The written set accumulates over the whole class; restoreWrittenTables()
 * is idempotent, so restoring the union of all tables written so far before
 * every test method is correct.
 *
 * The dirty flag is set by TrackingDatabase for every statement it could
 * not classify as a tracked write or a known safe read (DDL such as
 * CREATE/DROP/ALTER/TRUNCATE, stored procedure calls, unrecognized
 * statements). While it is set, restoreWrittenTables() widens the candidate
 * list to every auto-increment table, because such a statement either
 * implicitly committed the per-test transaction or may have written data
 * that was never recorded.
 *
 * DatabaseTestCase resets the tracker around every provisioning so that
 * provisioning writes never count as test writes: in setUpBeforeClass()
 * before provisioning, after the schema SQL and after the one-time fixture
 * rebuild (PROVISION_ON_SETUP_BEFORE_CLASS mode), and in setUp() after the
 * per-test schema provisioning (PROVISION_ON_SETUP mode). The dirty flag is
 * additionally cleared before and after every restore in
 * restoreWrittenTables(), so at the start of a restore it always reflects
 * exactly the statements of the test method that just finished.
 *
 * InnoDB does not roll back auto-increment counters together with the
 * per-test transaction, and on MySQL 8.4+ the live counter can neither be
 * read reliably (information_schema lags behind it) nor be lowered with
 * ALTER TABLE. The framework therefore restores every table in the written
 * set (or, while dirty, every auto-increment table) to its exact
 * post-provisioning state before each test method instead of reading or
 * adjusting counters.
 *
 * All table names are stored without the table prefix. Recording itself
 * happens in TrackingDatabase, which knows the prefix.
 *
 * @package ilch_phpunit
 */
final class WriteTracker
{
    /**
     * @var array<string, bool>
     */
    private static array $written = [];

    /**
     * Whether a statement was seen that the tracker could not classify as
     * a tracked write or a known safe read (DDL, stored procedure calls,
     * unrecognized statements).
     *
     * Such a statement either implicitly committed the per-test
     * transaction or may have written data that was never recorded, so the
     * next restoreWrittenTables() run must cover every auto-increment
     * table, not only the tracked ones. Cleared by reset() and by
     * restoreWrittenTables() before and after the restore.
     *
     * @var bool
     */
    private static bool $dirty = false;

    /**
     * Forgets every table recorded so far and clears the dirty flag.
     */
    public static function reset(): void
    {
        self::$written = [];
        self::$dirty = false;
    }

    /**
     * @return array<string, bool>
     */
    public static function written(): array
    {
        return self::$written;
    }

    /**
     * @param string|null $table table name without prefix
     */
    public static function recordTable(?string $table): void
    {
        if ($table !== null && $table !== '') {
            self::$written[$table] = true;
        }
    }

    /**
     * Marks the state as dirty - see $dirty.
     */
    public static function markDirty(): void
    {
        self::$dirty = true;
    }

    /**
     * Whether the state is dirty - see $dirty.
     *
     * @return bool
     */
    public static function isDirty(): bool
    {
        return self::$dirty;
    }

    /**
     * Clears the dirty flag.
     */
    public static function clearDirty(): void
    {
        self::$dirty = false;
    }
}
