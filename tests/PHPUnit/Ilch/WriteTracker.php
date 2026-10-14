<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

/**
 * Per-class bookkeeping of the tables test methods wrote to.
 *
 * The tracker is reset in setUpBeforeClass() (once before provisioning and
 * once after the schema SQL ran) and read by
 * DatabaseTestCase::restoreWrittenTables() at the start of every test
 * method.
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
