<?php
/**
 * Holds class PHPUnit_Ilch_DatabaseTestCase.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Base class for database test cases for Ilch.
 *
 * Should be used when using a mock for the database is not possible
 * or an extraordinarily huge effort.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class PHPUnit_Ilch_DatabaseTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * A data array which will be used to create a config object for the registry.
	 *
	 * @var Array
	 */
	protected $_configData = array();

	/**
	 * Only instantiate pdo once for test clean-up/fixture load
	 *
	 * @var [type]
	 */
    static private $pdo = null;

    /**
     * instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     *
     * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $conn = null;

	/**
	 * Filling the config object with individual testcase data.
	 */
	public function setUp()
	{
		parent::setUp();

		if(!\Ilch\Registry::has('config') && file_exists(__DIR__.'/../../../config.php'))
		{
		    $config = new \Ilch\Config\File();
		    $config->loadConfigFromFile(__DIR__.'/../../../config.php');
		    \Ilch\Registry::set('config', $config);
		}

		$config = \Ilch\Registry::get('config');

		foreach($this->_configData as $configKey => $configValue)
		{
			$config->set($configKey, $configValue);
		}
	}

	/**
	 * Creates the db connection to the test database.
	 *
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
    final public function getConnection()
    {
        if($this->conn === null)
        {
            if(self::$pdo == null)
            {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return PHPUnit_Extensions_Database_DataSet
     */
    public function getDataSet(){}
}