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
     * Files used for creating the database schema
     * @var array
     */
    protected static $dbSchemaFiles = [__DIR__ . '/_files/db_schema.sql'];

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
     * The db instance to test with.
     *
     * @var \Ilch\Database\MySQL
     */
    protected $db;

    /**
     * This method is called before the first test of this test class is run.
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
        }
        $db->queryMulti(static::getSchemaSQLQueries());
    }

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp()
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

        parent::setUp();
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        return array_reduce(self::$dbSchemaFiles, static function($carry, $fileName) {
            $carry .= file_get_contents($fileName);
            return $carry;
        }, '');
    }

    /**
     * Deleting all tables from the db
     *
     * @param \Ilch\Database\Mysql $db
     */
    protected static function dropTables(\Ilch\Database\Mysql $db)
    {
        $sql = 'SHOW TABLES';
        $tableList = $db->queryList($sql);

        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tableList as $table) {
            $sql = 'DROP TABLE ' . $table;
            $db->query($sql);
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
