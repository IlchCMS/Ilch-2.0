<?php
/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry as Registry;
use Ilch\Database\Factory as Factory;
use Ilch\Config\File as Config;

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
    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var array
     */
    static protected $configData = array();

    /**
     * Only instantiate pdo once for test clean-up/fixture load
     *
     * @static Static so we can don't have to connect for every test again.
     * @var \PDO
     */
    static private $pdo = null;

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
     * Setup config
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        TestHelper::setConfigInRegistry(static::$configData);
    }

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp()
    {
        $dbFactory = new Factory();

        if (!isset($this->db)) {
            Registry::remove('db');
            $config = $this->getConfig();
            $this->db = $dbFactory->getInstanceByConfig($config);
            Registry::set('db', $this->db);
        }


        if ($this->db === false) {

            $this->markTestIncomplete('Necessary DB configuration is not set.');
            parent::setUp();
            return;
        }

        /*
         * Deleting all tables from the db and setting up the db using the given schema.
         */
        $sql = 'SHOW TABLES';
        $tableList = $this->db->queryList($sql);

        foreach ($tableList as $table) {
            $sql = 'DROP TABLE ' . $table;
            $this->db->query($sql);
        }

        $this->db->queryMulti($this->getSchemaSQLQueries());

        parent::setUp();
    }

    /**
     * Creates the db connection to the test database.
     *
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection|null Returns null if the necessary config was not set
     */
    final public function getConnection()
    {
        $dbData = array();
        $config = $this->getConfig();

        foreach (array('dbEngine', 'dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $configKey) {
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
    protected function getSchemaSQLQueries()
    {
        return file_get_contents(__DIR__ . '/_files/db_schema.sql');
    }

    /**
     * Returns config or marks test as skipped if config could not be loaded
     *
     * @return Config|null
     */
    protected function getConfig()
    {
        $config = Registry::get('config');

        if (!$config instanceof Config) {
            $this->markTestSkipped('Necessary DB configuration is not set.');
        }

        return $config;
    }
}
