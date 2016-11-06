<?php
/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry as Registry;
use Ilch\Database\Factory as Factory;

/**
 * Base class for database test cases for Ilch.
 *
 * Should be used when using a mock for the database is not possible
 * or an extraordinarily huge effort.
 *
 * @package ilch_phpunit
 */
abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /** @var int don't automatically provision - for individual proovsioning */
    const PROVISION_DISABLED = 0;
    /** @var int automatically setup and provision db in setUp - for every testMethod */
    const PROVISION_ON_SETUP = 1;
    /** @var int automatically setup and provision db in setUpBeforeClass - just once per class - for reading tests*/
    const PROVISION_ON_SETUP_BEFORE_CLASS = 2;

    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var array
     */
    static protected $configData = [];

    /**
     * Only instantiate pdo once for test clean-up/fixture load
     *
     * @static Static so we can don't have to connect for every test again.
     * @var \PDO
     */
    static private $pdo = null;

    /**
     * @var bool
     */
    static protected $dropTablesOnProvision = true;

    /**
     * Whether the db is provisioned in setUp (true) or setUpBeforeClass (false)
     * @var int
     */
    static protected $fillDbOnSetUp = self::PROVISION_ON_SETUP;

    /**
     * Files used for creating the database schema
     * @var array
     */
    protected static $dbSchemaFiles = [__DIR__ . '/_files/db_schema.sql'];

    /**
     * Instantiated PHPUnit_Extensions_Database_DB_IDatabaseConnection for the tests.
     *
     * @var \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $conn = null;

    /**
     * The db instance to test with.
     *
     * @var \Ilch\Database\MySQL
     */
    protected $db = null;

    /**
     * Save provisioning state for PROVISION_ON_SETUP_BEFORE_CLASS
     * @var bool
     */
    private $dbProvisioned;

    /**
     * Setup config
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        TestHelper::setConfigInRegistry(static::$configData);

        if (!Registry::has('db')) {
            $dbFactory = new Factory();

            $config = Registry::get('config');
            Registry::set('db', $dbFactory->getInstanceByConfig($config));
        }

        if (static::PROVISION_ON_SETUP_BEFORE_CLASS) {
            $db = Registry::get('db');
            if (static::$dropTablesOnProvision) {
                static::dropTables($db);
            }
            $db->queryMulti(static::getSchemaSQLQueries());
        }
    }

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp()
    {
        $this->db = Registry::get('db');

        if ($this->db === false) {
            $this->markTestIncomplete('Necessary DB configuration is not set.');
            parent::setUp();
            return;
        }

        /*
         * Deleting all tables from the db and setting up the db using the given schema
         */
        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP) {
            if (static::$dropTablesOnProvision) {
                static::dropTables($this->db);
            }
            $this->db->queryMulti(static::getSchemaSQLQueries());
        }

        parent::setUp();
    }

    /**
     * Creates the db connection to the test database.
     *
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection|null Returns null if the necessary config was not set
     */
    final public function getConnection()
    {
        $dbData = [];
        $config = Registry::get('config');

        foreach (['dbEngine', 'dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix'] as $configKey) {
            /*
             * Using the data for the db from the config.
             * We check if special config variables for this test execution exist.
             * If so we gonna use it. Otherwise we have to skip the tests.
             */
            if ($config->get($configKey) !== null) {
                $dbData[$configKey] = $config->get($configKey);
            } else {
                $this->markTestSkipped('Necessary DB configuration is not set.');
            }
        }

        $dsn = strtolower($dbData['dbEngine']) . ':dbname=' . $dbData['dbName'] . ';host=' . $dbData['dbHost'];
        $dbData['dbDsn'] = $dsn;

        if ($this->conn === null) {
            if (self::$pdo === null) {
                self::$pdo = new \PDO($dbData['dbDsn'], $dbData['dbUser'], $dbData['dbPassword']);
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo, $dbData['dbName']);
        }

        return $this->conn;
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        return array_reduce(self::$dbSchemaFiles, function($carry, $fileName) {
            $carry .= file_get_contents($fileName);
            return $carry;
        }, '');
    }

    /**
     * Deleting all tables from the db
     * @param \Ilch\Database\Mysql $db
     */
    protected static function dropTables(\Ilch\Database\Mysql $db)
    {
        $sql = 'SHOW TABLES';
        $tableList = $db->queryList($sql);

        foreach ($tableList as $table) {
            $sql = 'DROP TABLE ' . $table;
            $db->query($sql);
        }
    }

    /**
     * Returns the database operation executed in test setup.
     *
     * @return \PHPUnit_Extensions_Database_Operation_IDatabaseOperation
     */
    protected function getSetUpOperation()
    {
        if (static::$fillDbOnSetUp === self::PROVISION_ON_SETUP_BEFORE_CLASS) {
            if ($this->dbProvisioned) {
                return \PHPUnit_Extensions_Database_Operation_Factory::NONE();
            }
            $this->dbProvisioned = true;
        } elseif (static::$fillDbOnSetUp === self::PROVISION_DISABLED) {
            return \PHPUnit_Extensions_Database_Operation_Factory::NONE();
        }

        return \PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
    }
}
