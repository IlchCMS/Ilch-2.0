<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Database\Exception;
use Ilch\Database\Mysql;

/**
 * Drop-in replacement for Ilch\Database\Mysql that records which tables
 * received INSERT/REPLACE/UPDATE/DELETE statements.
 *
 * The instance wraps an existing Mysql instance: it copies all of its
 * properties (most importantly the mysqli connection) and shares the very
 * same database session. Queries are executed exactly as before; only the
 * written tables are additionally recorded in the WriteTracker.
 *
 * Table names are recorded without the table prefix, matching the names the
 * query builders (and the YAML fixtures) use.
 *
 * @package ilch_phpunit
 */
class TrackingDatabase extends Mysql
{
    /**
     * Keeps the wrapped instance alive. Otherwise its destructor would run
     * as soon as the Registry holds this tracker and would close the shared
     * mysqli connection while this tracker is still in use.
     *
     * @var Mysql
     */
    private Mysql $realInstance;

    /**
     * Wraps the given Mysql instance, sharing its live connection without
     * opening a second one.
     *
     * @param Mysql $real the Mysql instance to wrap
     */
    public function __construct(Mysql $real)
    {
        $this->realInstance = $real;

        // Copy every property of the wrapped instance - most importantly the
        // mysqli connection, so all statements run in the very same database
        // session - without running the parent constructor, which would open
        // a second connection.
        $reflection = new \ReflectionClass(Mysql::class);
        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setAccessible(true);
            $property->setValue($this, $property->getValue($real));
        }
    }

    /**
     * Intentionally empty - must not close the shared connection here.
     *
     * The wrapped instance is kept alive by $realInstance and therefore
     * destroyed after this tracker; its destructor closes the shared mysqli
     * connection exactly once. Closing it a second time would throw
     * "mysqli object is already closed" on PHP 8.
     */
    public function __destruct()
    {
        // Must NOT close the shared connection here: this tracker and the
        // wrapped instance reference the very same mysqli object, and on
        // PHP 8 a second close() throws "mysqli object is already closed".
        //
        // The wrapped instance is kept alive by $realInstance, so it is
        // always destroyed AFTER this tracker - and its destructor closes
        // the connection exactly once.
    }

    /**
     * Returns a new insert builder and records the target table as written.
     *
     * @param string|null $into table to insert into (with or without prefix)
     * @param array|null $values optional column => value map to insert
     * @return Mysql\Insert
     */
    public function insert($into = null, $values = null): Mysql\Insert
    {
        WriteTracker::recordTable($this->normalizeTable($into));

        return parent::insert($into, $values);
    }

    /**
     * Returns a new update builder and records the target table as written.
     *
     * @param string|null $table table to update (with or without prefix)
     * @param array|null $values optional column => value map to set
     * @param array|null $where optional where condition
     * @return Mysql\Update
     */
    public function update($table = null, $values = null, $where = null): Mysql\Update
    {
        WriteTracker::recordTable($this->normalizeTable($table));

        return parent::update($table, $values, $where);
    }

    /**
     * Returns a new delete builder and records the target table as written.
     *
     * @param string|null $from table to delete rows from (with or without prefix)
     * @param array|null $where optional where condition
     * @return Mysql\Delete
     */
    public function delete($from = null, $where = null): Mysql\Delete
    {
        WriteTracker::recordTable($this->normalizeTable($from));

        return parent::delete($from, $where);
    }

    /**
     * Runs a single SQL statement and records the target table when the
     * statement is an INSERT, REPLACE, UPDATE or DELETE.
     *
     * @param string $sql
     * @return \mysqli_result|bool
     * @throws Exception
     */
    public function query(string $sql): \mysqli_result|bool
    {
        $this->recordWriteSql($sql);

        return parent::query($sql);
    }

    /**
     * Runs several semicolon separated SQL statements and records the target
     * table of every INSERT, REPLACE, UPDATE or DELETE among them.
     *
     * @param string $sql semicolon separated SQL statements
     * @return bool
     */
    public function queryMulti(string $sql): bool
    {
        foreach (explode(';', $sql) as $statement) {
            $this->recordWriteSql($statement);
        }

        return parent::queryMulti($sql);
    }

    /**
     * Records the target table of INSERT/REPLACE/UPDATE/DELETE statements.
     *
     * Leading SQL comments are stripped first so a commented statement is
     * still detected. Over-matching is harmless (recording a table that was
     * never written only costs an idempotent recreation); only under-
     * matching is dangerous, so the pattern is deliberately broad.
     *
     * @param string $sql
     */
    private function recordWriteSql(string $sql): void
    {
        $sql = $this->stripLeadingComments($sql);

        if ($sql === '') {
            return;
        }

        if (
            preg_match(
                '/^(?:INSERT(?:\s+IGNORE)?\s+INTO\s+|REPLACE(?:\s+IGNORE)?\s+INTO\s+|REPLACE\s+|UPDATE\s+|DELETE\s+FROM\s+)`?([A-Za-z0-9_\[\]]+)`?/i',
                $sql,
                $matches
            )
        ) {
            WriteTracker::recordTable($this->normalizeTable($matches[1]));
        }
    }

    /**
     * Removes leading SQL comments (block comments and # / -- line
     * comments) and the whitespace between them, so detection works on
     * commented statements. Only affects detection - the SQL handed to the
     * parent is always the original string.
     *
     * @param string $sql
     * @return string
     */
    private function stripLeadingComments(string $sql): string
    {
        do {
            $previous = $sql;
            $sql = (string) preg_replace('/^\s*(?:\/\*.*?\*\/|\#[^\n]*$|--[^\n]*$)/ms', '', $sql);
        } while ($sql !== $previous);

        return $sql;
    }

    /**
     * Reduces a table name (with optional "[prefix]_" marker and/or the real
     * table prefix) to the plain name used by the query builders and the
     * YAML fixtures.
     *
     * @param string|null $table
     * @return string|null
     */
    private function normalizeTable(?string $table): ?string
    {
        if ($table === null || $table === '') {
            return null;
        }

        $table = str_replace('[prefix]_', '', $table);

        $prefix = $this->getPrefix();
        if ($prefix !== '' && str_starts_with($table, $prefix)) {
            $table = substr($table, strlen($prefix));
        }

        return $table !== '' ? $table : null;
    }
}
