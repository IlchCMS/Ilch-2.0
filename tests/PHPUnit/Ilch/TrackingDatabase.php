<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Database\Mysql;

/**
 * Drop-in replacement for Ilch\Database\Mysql that records which tables
 * received INSERT/UPDATE/DELETE statements.
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
    private $realInstance;

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

    public function insert($into = null, $values = null)
    {
        WriteTracker::recordTable($this->normalizeTable($into));

        return parent::insert($into, $values);
    }

    public function update($table = null, $values = null, $where = null)
    {
        WriteTracker::recordTable($this->normalizeTable($table));

        return parent::update($table, $values, $where);
    }

    public function delete($from = null, $where = null)
    {
        WriteTracker::recordTable($this->normalizeTable($from));

        return parent::delete($from, $where);
    }

    public function query(string $sql)
    {
        $this->recordWriteSql($sql);

        return parent::query($sql);
    }

    public function queryMulti(string $sql): bool
    {
        foreach (explode(';', $sql) as $statement) {
            $this->recordWriteSql($statement);
        }

        return parent::queryMulti($sql);
    }

    /**
     * Records the target table of INSERT/UPDATE/DELETE statements.
     *
     * @param string $sql
     */
    private function recordWriteSql(string $sql): void
    {
        $sql = trim($sql);

        if ($sql === '') {
            return;
        }

        if (
            preg_match(
                '/^(?:INSERT(?:\s+IGNORE)?\s+INTO|UPDATE|DELETE\s+FROM)\s+`?([A-Za-z0-9_\[\]]+)`?/i',
                $sql,
                $matches
            )
        ) {
            WriteTracker::recordTable($this->normalizeTable($matches[1]));
        }
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
        if ($prefix !== '' && strpos($table, $prefix) === 0) {
            $table = substr($table, strlen($prefix));
        }

        return $table !== '' ? $table : null;
    }
}
