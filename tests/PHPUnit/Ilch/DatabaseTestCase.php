<?php

/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry;
use Ilch\Database\Factory;

/**
 * Base class for database test cases for Ilch.
 *
 * Should be used when using a mock for the database is not possible
 * or an extraordinarily huge effort.
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
     * setUpBeforeClass() load them once, right after provisioning, with
     * TRUNCATE semantics. The auto-increment state afterwards is exactly the
     * state the old per-test drop/recreate produced right before every test
     * method, and it becomes the per-test reset base (see
     * $baseAutoIncrements).
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
     * Base auto-increment value per table, measured from information_schema
     * directly after provisioning plus the one-time fixture load in
     * setUpBeforeClass().
     *
     * That state is exactly what the old per-test drop/recreate produced
     * right before every test method: the fixture rows (TRUNCATE + insert) of
     * the fixture's tables and the state the schema SQL left all other tables
     * in (CREATE-time AUTO_INCREMENT options plus install scripts' seed
     * rows).
     *
     * Measuring instead of parsing the CREATE statements is essential,
     * because InnoDB never allows an auto-increment counter below
     * "maximum id in the table plus one". The value measured here always
     * satisfies that bound for the committed rows, so restoring a drifted
     * counter to its base with ALTER TABLE is never clamped and lands
     * exactly on the base.
     *
     * InnoDB does NOT roll back auto-increment counters together with the
     * transaction, so inserts of a rolled-back test leave the counter
     * advanced. The counters are therefore restored before every test method
     * (see resetAutoIncrements()).
     *
     * @var array<string, int>
     */
    protected static $baseAutoIncrements = [];

    /**
     * Whether the auto-increment bookkeeping of the current class has been
     * verified once (on the first reset that actually performed ALTERs).
     *
     * @var bool
     */
    protected static $autoIncrementResetVerified = false;

    /**
     * The db instance to test with.
     *
     * @var \Ilch\Database\MySQL
     */
    protected $db;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        TestHelper::setConfigInRegistry(static::$configData);

        if (!Registry::has('db')) {
            $dbFactory = new Factory();

            $config = Registry::get('config');

            if (!$config) {
                throw new \InvalidArgumentException('Config was null. config.php in tests folder might be missing.');
            }

            Registry::set('db', $dbFactory->getInstanceByConfig($config));
        }

        static::$autoIncrementResetVerified = false;

        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP_BEFORE_CLASS) {
            $db = Registry::get('db');

            if (static::$dropTablesOnProvision) {
                static::dropTables($db);
            }

            $db->queryMulti(static::getSchemaSQLQueries());

            // Load the fixture once, with TRUNCATE semantics and outside any
            // transaction (the implicit commits are harmless here). This
            // reproduces the auto-increment state the old per-test
            // drop/recreate produced right before every test method:
            // TRUNCATE resets the counters of the fixture's tables, and all
            // other tables keep the counters the schema SQL created for them
            // (CREATE options plus install scripts' seed rows).
            foreach (static::$fixtureFiles as $fixtureFile) {
                $dataset = new PhpunitDataset($db);
                $dataset->loadFromFile($fixtureFile, true);
            }

            // Right after provisioning plus the fixture load,
            // information_schema holds exactly the counters a test method
            // must start with.
            static::$baseAutoIncrements = static::snapshotAutoIncrements($db);
        } else {
            static::$baseAutoIncrements = [];
        }
    }

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp(): void
    {
        $this->db = Registry::get('db');

        if ($this->db === false) {
            self::markTestIncomplete('Necessary DB configuration is not set.');
            parent::setUp();
            return;
        }

        // Deleting all tables from the db and setting up the db using the given schema
        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP) {
            if (static::$dropTablesOnProvision) {
                static::dropTables($this->db);
            }

            $this->db->queryMulti(static::getSchemaSQLQueries());
        }

        // InnoDB does not roll back auto-increment counters, so a rolled-back
        // test leaves them advanced. Restore the base values - exactly what
        // the old per-test drop/recreate used to yield - for every table
        // whose counter drifted.
        //
        // Must run BEFORE "START TRANSACTION": ALTER TABLE is DDL and would
        // implicitly commit an open transaction.
        if (
            static::$isolateTestsInTransaction
            && static::$fillDbOnSetUp === self::PROVISION_ON_SETUP_BEFORE_CLASS
        ) {
            static::resetAutoIncrements($this->db);
        }

        // Start the per-test isolation transaction. Subclasses must do all
        // fixture loading after parent::setUp() so it happens inside the
        // transaction.
        if (static::$isolateTestsInTransaction) {
            $this->db->query('START TRANSACTION');
        }

        parent::setUp();
    }

    /**
     * Roll back the per-test isolation transaction, if any.
     */
    protected function tearDown(): void
    {
        if (static::$isolateTestsInTransaction && $this->db !== null && $this->db !== false) {
            $this->db->query('ROLLBACK');
        }

        parent::tearDown();
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        return array_reduce(static::$dbSchemaFiles, static function ($carry, $fileName) {
            $carry .= file_get_contents($fileName);

            return $carry;
        }, '');
    }

    /**
     * Reads the current auto-increment value of every table in the database
     * that has an auto-increment column.
     *
     * @param \Ilch\Database\MySQL $db
     * @return array<string, int> table name => next auto-increment value
     */
    protected static function snapshotAutoIncrements(\Ilch\Database\MySQL $db): array
    {
        // queryList() only returns the first column of every row
        // (see dropTables()), so both values are combined into one column.
        // A tab is used as separator because it can never appear in a
        // table name.
        $rows = $db->queryList(
            "SELECT CONCAT(TABLE_NAME, '\t', AUTO_INCREMENT) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()"
        );

        $values = [];
        foreach ($rows as $row) {
            // Tables without an auto-increment column produce NULL here.
            if (!is_string($row)) {
                continue;
            }

            $parts = explode("\t", $row);
            if (count($parts) === 2) {
                $values[$parts[0]] = (int) $parts[1];
            }
        }

        return $values;
    }

    /**
     * Restores the auto-increment counters of the schema tables to their base
     * values - only for tables whose counter actually differs, i.e. the
     * tables the previous test inserted into. Everything else is a no-op, so
     * the per-test cost is one small SELECT plus a couple of ALTERs.
     *
     * @param \Ilch\Database\MySQL $db
     */
    protected static function resetAutoIncrements(\Ilch\Database\MySQL $db): void
    {
        if (static::$baseAutoIncrements === []) {
            // No auto-increment tables for this class - nothing to restore.
            return;
        }

        $current = static::snapshotAutoIncrements($db);

        if ($current === []) {
            // The schema defines auto-increment tables, so after provisioning
            // the information_schema query must see at least one of them.
            // An empty result here means the bookkeeping below would silently
            // be a no-op for every test - fail loudly instead.
            throw new \RuntimeException(
                'DatabaseTestCase: reading auto-increment values from information_schema.TABLES '
                . 'returned no rows, although the schema defines auto-increment tables. '
                . 'The per-test auto-increment reset cannot work. Check the test database connection.'
            );
        }

        $altered = [];
        foreach (static::$baseAutoIncrements as $table => $base) {
            // A table that no longer exists (dropped inside a previous test of
            // this class, e.g. an uninstall test) is simply skipped.
            if (!isset($current[$table])) {
                continue;
            }

            if ($current[$table] !== $base) {
                $db->query(sprintf('ALTER TABLE `%s` AUTO_INCREMENT = %d', $table, $base));
                $altered[] = $table;
            }
        }

        // Verify once per class that an actually performed reset took effect.
        // With the measured base this can only fail if a test changed the
        // committed row set of a table in a way that outlives the rollback.
        if (!static::$autoIncrementResetVerified && $altered !== []) {
            static::$autoIncrementResetVerified = true;

            $after = static::snapshotAutoIncrements($db);
            $mismatches = [];
            foreach ($altered as $table) {
                $expected = static::$baseAutoIncrements[$table];
                if (!isset($after[$table]) || $after[$table] !== $expected) {
                    $mismatches[$table] = ['expected' => $expected, 'actual' => $after[$table] ?? null];
                }
            }

            if ($mismatches !== []) {
                throw new \RuntimeException(
                    'DatabaseTestCase: auto-increment reset did not take effect: '
                    . json_encode($mismatches)
                );
            }
        }
    }

    /**
     * Deleting all tables from the db
     *
     * @param \Ilch\Database\MySQL $db
     */
    protected static function dropTables(\Ilch\Database\MySQL $db)
    {
        $sql = 'SHOW TABLES';
        $tableList = $db->queryList($sql);

        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tableList as $table) {
            $db->drop($table, true);
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
