<?php
/**
 * Holds class Libraries_Ilch_ConfigTest.
 *
 * @author Meyer Dominik
 * @package ilch_phpunit
 */

/**
 * Tests the config object.
 *
 * @author Meyer Dominik
 * @package ilch_phpunit
 */
class Libraries_Ilch_ConfigTest extends PHPUnit_Ilch_TestCase
{
	/**
	 * The object to test with.
	 *
	 * @var Ilch_Config
	 */
	protected $_config;

	/**
	 * Initializes an empty config object.
	 */
	public function setUp()
	{
		$this->_config = new Ilch_Config();
	}

	/**
	 * Tests if a set config can be given back without a manipulation.
	 */
	public function testSetAndGetConfig()
	{
		$this->_config->setConfig('email', 'testuser@testmail.com');
		$this->assertEquals
		(
				'testuser@testmail.com',
				$this->_config->getConfig('email'),
				'Config value got manipulated unexpectedly.'
		);
	}

	/**
	 * Tests if a loaded config file is the same as the defined array.
	 */
	public function testLoadConfigFromFile()
	{
		$configArray = array
		(
			'dbHost' => 'localhost',
			'dbUser' => 'root',
			'dbPassword' => ''
		);

		$this->_config->loadConfigFromFile($this->_getFilesFolder().'/config.php');

		$this->assertEquals
		(
				$configArray,
				array
				(
					'dbHost' => $this->_config->getConfig('dbHost'),
					'dbUser' => $this->_config->getConfig('dbUser'),
					'dbPassword' => $this->_config->getConfig('dbPassword')
				),
				'Config array from file differs with defined array.'
		);
	}
}