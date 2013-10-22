<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database;
defined('ACCESS') or die('no direct access');

class Factory
{
	/**
	 * Gets database adapter by config.
	 *
	 * @param \Ilch\Config\File $config
	 * @return \Ilch\Database\*
	 */
	public function getInstanceByConfig(\Ilch\Config\File $config)
	{
		foreach(array('dbEngine', 'dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $configKey)
		{
			/*
			 * Using the data for the db from the config.
			 * If the constant PHPUNIT_TEST is set, we check if special config variables
			 * for this test execution exist. If so we gonna use it.
			 */
			if(defined('PHPUNIT_TEST') && $config->get($configKey.'Test') !== null)
			{
				$dbData[$configKey] = $config->get($configKey.'Test');
			}
			else
			{
				$dbData[$configKey] = $config->get($configKey);
			}
		}

		$dbClass = '\\Ilch\\Database\\'.$dbData['dbEngine'];
		$db = new $dbClass();
		$db->connect($dbData['dbHost'], $dbData['dbUser'], $dbData['dbPassword']);
		$db->setDatabase($dbData['dbName']);
		$db->setPrefix($dbData['dbPrefix']);

		return $db;
	}

	/**
	 * Gets database adapter by engine name.
	 *
	 * @param string $engine
	 * @return \Ilch\Database\*
	 */
	public function getInstanceByEngine($engine)
	{
		$engine = '\\Ilch\\Database\\'.$engine;
		$db = new $engine();
		return $db;
	}
}