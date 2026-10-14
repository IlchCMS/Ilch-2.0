<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry;
use Ilch\Database\Factory;
use Ilch\Database\Mysql;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for database test cases for Ilch.
 *
 * Should be used when using a mock for the database is not possible
 * or an extraordinarily huge effort.
 *
 * Data isolation per test method:
 *
 * Every test method runs in its own transaction that is rolled back in
 * tearDown(). InnoDB does not roll back auto-increment counters together
 * with that transaction, and on MySQL 8.4+ the live counter can neither be
 * read reliably from information_schema (the value lags behind the real
 * one) nor be lowered with ALTER TABLE. The framework therefore does not
 * read or adjust counters at all: it tracks which tables a test method wrote
 * to and restores those tables to their exact post-provisioning state before
 * the next test method (DROP + CREATE + re-insert of the base rows). That
 * reproduces the state the old full per-test schema drop/recreate produced -
 * on every MySQL/MariaDB version - without recreating the whole schema.
 *
 * @package ilch_phpunit
 */
abstract class DatabaseTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var int don't automatically provision - for individual provisioning */
    public const PROVISION_DISABLED = 0;
    /** @var int automatically setup and provision db in setUp - for every testMethod */
    public const PROVISION_ON_SETUP = 1;
    /** @var int automatically setup and provision db in setUpBeforeClass - just once per class - for reading tests */
    public const PROVISION_ON_SETUP_BEFORE_CLASS = 2;

    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var array
     */
    protected static $configData = [];

    /**
     * Files used for creating the database schema
     *
     * @var array
     */
    protected static $dbSchemaFiles = [__DIR__ . '/_files/db_schema.sql'];

    /**
     * @var bool
     */
    protected static $dropTablesOnProvision = true;

    /**
     * When the db is provisioned.
     *
     * Default is once per class (setUpBeforeClass). PROVISION_ON_SETUP drops
     * and recreates the whole schema for every single test method and is
     * very expensive - only use it if a test really needs a freshly created
     * schema.
     *
     * @var int
     */
    protected static $fillDbOnSetUp = self::PROVISION_ON_SETUP_BEFORE_CLASS;

    /**
     * Whether every test method is wrapped in its own transaction that is
     * rolled back in tearDown().
     *
     * This restores the per-test data isolation that the old per-test schema
     * drop/recreate used to provide, at a fraction of the cost.
     *
     * NOTE: fixture loading must happen in setUp() AFTER parent::setUp() and
     * must not contain DDL - TRUNCATE/CREATE/DROP implicitly commit and would
     * break the isolation. PhpunitDataset uses DELETE for this reason.
     *
     * @var bool
     */
    protected static $isolateTestsInTransaction = true;

    /**
     * YAML fixture files that are loaded before every test method.
     *
     * Declaring them here (and not only in the subclass's setUp()) lets
     * setUpBeforeClass() rebuild them once, right after provisioning.
     *
     * Every test class that loads a fixture in its setUp() must declare the
     * file(s) here and load them from this property:
     *
     *     protected static $fixtureFiles = [__DIR__ . '/_files/xxx_database.yml'];
     *
     *     protected function setUp(): void
     *     {
     *         parent::setUp();
     *
     *         $this->phpunitDataset = new PhpunitDataset($this->db);
     *         $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
     *     }
     *
     * @var array
     */
    protected static $fixtureFiles = [];

    /**
     * Tables covered by the fixtures of this class (keys of the YAML files),
     * without table prefix.
     *
     * @var array<string, bool>
     */
    protected static $fixtureTables = [];

    /**
     * All tables of the schema that have an auto-increment column, without
     * table prefix.
     *
     * @var array<string, bool>
     */
    protected static $autoIncrementTables = [];

    /**
     * "SHOW CREATE TABLE" definitions, captured right after provisioning, of
     * every fixture table and auto-increment table. Keys are table names
     * without prefix; the definitions themselves contain the real (prefixed)
     * name and are replayed verbatim.
     *
     * For fixture tables the definition is replayed with AUTO_INCREMENT=1 -
     * for the others it is replayed as captured. Together with the re-inserted
     * base rows this reproduces the exact counter state the schema
     * provisioning left behind, on every MySQL/MariaDB version.
     *
     * @var array<string, string>
     */
    protected static $tableDefinitions = [];

    /**
     * Committed base rows (state right after provisioning) of every
     * auto-increment table that is not covered by a fixture.
     *
     * @var array<string, array>
     */
    protected static $seedRows = [];

    /**
     * The db instance to test with.
     *
     * @var Mysql
     */
    protected $db;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        TestHelper::setConfigInRegistry(static::$configData);

        $db = Registry::get('db');

        if (!$db instanceof TrackingDatabase) {
            if (!$db) {
                $config = Registry::get('config');

                if (!$config) {
                    throw new \InvalidArgumentException('Config was null. config.php in tests folder might be missing.');
                }

                $db = (new Factory())->getInstanceByConfig($config);
            }

            // Registry::set() throws when the key already exists, so remove the
            // previous value (raw Mysql, or a leftover from another class)
            // before storing the wrapper. The first class installs the tracker;
            // every later class reuses it - same shared connection throughout.
            Registry::remove('db');
            Registry::set('db', new TrackingDatabase($db));
        }

        static::$fixtureTables = [];
        static::$autoIncrementTables = [];
        static::$tableDefinitions = [];
        static::$seedRows = [];
        WriteTracker::reset();

        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP_BEFORE_CLASS) {
            if (static::$dropTablesOnProvision) {
                static::dropTables($db);
            }

            $db->queryMulti(static::getSchemaSQLQueries());

            // The schema SQL (including install scripts) is not a test write;
            // it is part of the provisioned state.
            WriteTracker::reset();

            // Collect the fixture tables and their rows.
            $fixtureData = [];
            foreach (static::$fixtureFiles as $fixtureFile) {
                $parsed = Yaml::parse((string) file_get_contents($fixtureFile));

                foreach ((array) $parsed as $table => $rows) {
                    static::$fixtureTables[$table] = true;

                    // A later file overrides an earlier one, exactly like the
                    // old sequential fixture loading did.
                    $fixtureData[$table] = (array) $rows;
                }
            }

            // Capture the definitions of every fixture table and every
            // auto-increment table. The "[prefix]_" marker is resolved by the
            // Mysql class itself.
            static::$autoIncrementTables = array_flip(static::getAutoIncrementTables($db));

            $tablesToCapture = array_unique(array_merge(
                array_keys(static::$fixtureTables),
                array_keys(static::$autoIncrementTables)
            ));

            foreach ($tablesToCapture as $table) {
                $row = $db->queryRow(sprintf('SHOW CREATE TABLE `[prefix]_%s`', $table));
                static::$tableDefinitions[$table] = static::extractCreateTable($row);
            }

            // Rebuild the fixture tables from scratch: DROP + CREATE with
            // AUTO_INCREMENT=1 + the fixture rows.
            //
            // This reproduces the TRUNCATE+insert state the old per-test
            // drop/recreate produced - without relying on TRUNCATE's
            // auto-increment behavior, which is not identical across MySQL
            // versions. A freshly created table always starts with a fresh
            // counter on every engine.
            $db->query('SET FOREIGN_KEY_CHECKS = 0;');

            foreach ($fixtureData as $table => $rows) {
                if (!$db->ifTableExists($table)) {
                    throw new \RuntimeException('Fixture table does not exist: ' . $table);
                }

                $db->drop($table, true);
                $db->query(static::definitionWithAutoIncrementOne(static::$tableDefinitions[$table]));

                foreach ($rows as $columns) {
                    $db->insert($table)->values($columns)->execute();
                }
            }

            $db->query('SET FOREIGN_KEY_CHECKS = 1;');

            // Snapshot the base rows of every auto-increment table that the
            // fixtures do not cover (the rows the schema SQL / install scripts
            // committed).
            foreach (array_keys(static::$autoIncrementTables) as $table) {
                if (isset(static::$fixtureTables[$table])) {
                    continue;
                }

                $rows = [];
                $result = $db->select('*')->from($table)->execute();
                while (($row = $result->fetchAssoc()) !== null) {
                    $rows[] = $row;
                }

                static::$seedRows[$table] = $rows;
            }
        }
    }

    protected function setUp(): void
    {
        $this->db = Registry::get('db');

        if ($this->db === false) {
            self::markTestIncomplete('Necessary DB configuration is not set.');
            parent::setUp();
            return;
        }

        // Drop all tables and set up the db using the given schema.
        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP) {
            if (static::$dropTablesOnProvision) {
                static::dropTables($this->db);
            }

            $this->db->queryMulti(static::getSchemaSQLQueries());

            // The schema SQL is not a test write.
            WriteTracker::reset();
        }

        // Restore every table a previous test method of this class wrote to
        // back to its exact post-provisioning state.
        //
        // InnoDB does not roll back auto-increment counters together with the
        // per-test transaction. On MySQL 8.4+ the live counter can neither be
        // read from information_schema (the value lags behind the real one)
        // nor be lowered with ALTER TABLE, so a per-test "ALTER ...
        // AUTO_INCREMENT = base" reset silently does nothing there and the
        // ids drift test over test. Recreating the table (DROP + CREATE +
        // re-insert of the base rows) gives a guaranteed-fresh counter on
        // every engine and only touches the few tables a test actually used.
        //
        // Must run before "START TRANSACTION": DROP/CREATE is DDL and would
        // implicitly commit an open transaction.
        if (
            static::$isolateTestsInTransaction
            && static::$fillDbOnSetUp === self::PROVISION_ON_SETUP_BEFORE_CLASS
        ) {
            static::restoreWrittenTables($this->db);
        }

        // Start the per-test isolation transaction. Subclasses must do all
        // fixture loading after parent::setUp() so it happens inside the
        // transaction.
        if (static::$isolateTestsInTransaction) {
            $this->db->query('START TRANSACTION');
        }

        parent::setUp();
    }

    protected function tearDown(): void
    {
        if (static::$isolateTestsInTransaction && $this->db !== null && $this->db !== false) {
            $this->db->query('ROLLBACK');
        }

        parent::tearDown();
    }

    /**
     * Restores every auto-increment table that a previous test method wrote
     * to (or that the fixtures of this class cover) back to its exact
     * post-provisioning state.
     *
     * @param Mysql $db
     */
    protected static function restoreWrittenTables(Mysql $db): void
    {
        $candidates = array_keys(array_merge(WriteTracker::written(), static::$fixtureTables));

        if ($candidates === []) {
            return;
        }

        $db->query('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($candidates as $table) {
            // Only tables with an auto-increment column can drift, and only
            // tables with a captured definition can be restored.
            if (!isset(static::$autoIncrementTables[$table]) || !isset(static::$tableDefinitions[$table])) {
                continue;
            }

            // Tables dropped by a previous test (e.g. an uninstall test) are
            // simply skipped.
            if (!$db->ifTableExists($table)) {
                continue;
            }

            $db->drop($table, true);

            if (isset(static::$fixtureTables[$table])) {
                // The per-test fixture load (inside the isolation transaction)
                // re-inserts the fixture rows; here we only need a fresh
                // counter.
                $db->query(static::definitionWithAutoIncrementOne(static::$tableDefinitions[$table]));
            } else {
                // Replay the captured definition (with the auto-increment value
                // the provisioning measured for it) and the committed base
                // rows.
                $db->query(static::$tableDefinitions[$table]);

                foreach (static::$seedRows[$table] as $row) {
                    static::insertRow($db, $table, $row);
                }
            }
        }

        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * Re-inserts a single row, preserving explicit NULLs.
     *
     * Mysql\Insert::generateSql() skips null values, which would silently
     * apply the column default instead of the value the row had.
     *
     * @param Mysql $db
     * @param string $table table without prefix
     * @param array $row column => value
     */
    protected static function insertRow(Mysql $db, string $table, array $row): void
    {
        $columns = [];
        $values = [];

        foreach ($row as $column => $value) {
            $columns[] = '`' . $column . '`';
            $values[] = ($value === null) ? 'NULL' : $db->escape($value, true);
        }

        $db->query(
            'INSERT INTO `[prefix]_' . $table . '` ('
            . implode(', ', $columns)
            . ') VALUES ('
            . implode(', ', $values)
            . ')'
        );
    }

    /**
     * Table names (without prefix) of every table in the database that has
     * an auto-increment column.
     *
     * Only the names are needed here - they are stable on every version.
     * (Reading the counter values themselves is exactly what does not work
     * reliably on MySQL 8.4+.)
     *
     * @param Mysql $db
     * @return array
     */
    protected static function getAutoIncrementTables(Mysql $db): array
    {
        $prefix = $db->getPrefix();
        $names = $db->queryList(
            'SELECT TABLE_NAME FROM information_schema.TABLES '
            . 'WHERE TABLE_SCHEMA = DATABASE() AND AUTO_INCREMENT IS NOT NULL'
        );

        $tables = [];
        foreach ($names as $name) {
            if ($prefix === '') {
                $tables[] = $name;
            } elseif (strpos($name, $prefix) === 0) {
                $tables[] = substr($name, strlen($prefix));
            }
        }

        return $tables;
    }

    /**
     * Rewrites the table definition with AUTO_INCREMENT=1 - the state
     * TRUNCATE would have produced for the counter.
     *
     * @param string $definition a "SHOW CREATE TABLE" output
     * @return string
     */
    protected static function definitionWithAutoIncrementOne(string $definition): string
    {
        if (preg_match('/AUTO_INCREMENT\s*=\s*\d+/i', $definition)) {
            return (string) preg_replace('/AUTO_INCREMENT\s*=\s*\d+/i', 'AUTO_INCREMENT=1', $definition);
        }

        // No explicit value in the definition - add one in front of the table
        // options.
        if (preg_match('/\)\s*ENGINE/i', $definition)) {
            return (string) preg_replace('/\)\s*ENGINE/i', ') AUTO_INCREMENT=1 ENGINE', $definition, 1);
        }

        return $definition;
    }

    /**
     * Extracts the "Create Table" column from a SHOW CREATE TABLE result row.
     *
     * @param array|null $row
     * @return string
     */
    protected static function extractCreateTable($row): string
    {
        if (is_array($row)) {
            if (isset($row['Create Table'])) {
                return (string) $row['Create Table'];
            }

            $values = array_values($row);
            if (count($values) >= 2) {
                return (string) $values[1];
            }
        }

        return (string) $row;
    }

    protected static function getSchemaSQLQueries(): string
    {
        return array_reduce(static::$dbSchemaFiles, static function ($carry, $fileName) {
            $carry .= file_get_contents($fileName);

            return $carry;
        }, '');
    }

    protected static function dropTables(Mysql $db): void
    {
        $tableList = $db->queryList('SHOW TABLES');

        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tableList as $table) {
            $db->drop($table, true);
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
