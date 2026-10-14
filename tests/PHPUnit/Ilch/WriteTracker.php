<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

/**
 * Per-class bookkeeping of the tables test methods wrote to.
 *
 * The set accumulates over the whole class; restoreWrittenTables() is
 * idempotent, so restoring the union of all tables written so far before
 * every test method is correct.
 *
 * DatabaseTestCase resets the tracker around every provisioning so that
 * provisioning writes never count as test writes: in setUpBeforeClass()
 * before provisioning, after the schema SQL and after the one-time fixture
 * rebuild (PROVISION_ON_SETUP_BEFORE_CLASS mode), and in setUp() after the
 * per-test schema provisioning (PROVISION_ON_SETUP mode).
 *
 * The tracker is read by DatabaseTestCase::restoreWrittenTables() at the
 * start of every test method. When
 * DatabaseTestCase::$restoreAllAutoIncrementTables is enabled, that method
 * instead recreates every auto-increment table and this set no longer drives
 * the candidate list - recording and resetting still happen unchanged.
 *
 * InnoDB does not roll back auto-increment counters together with the
 * per-test transaction, and on MySQL 8.4+ the live counter can neither be
 * read reliably (information_schema lags behind it) nor be lowered with
 * ALTER TABLE. The framework therefore restores every table in this set to
 * its exact post-provisioning state before each test method instead of
 * reading or adjusting counters.
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
     * Forgets every table recorded so far.
     */
    public static function reset(): void
    {
        self::$written = [];
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
}
