<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Database_Factory
{
	/**
	 * Gets database adapter by config.
	 *
	 * @param Ilch_Config $config
	 * @return Ilch_Database_*
	 */
	public function getInstanceByConfig(Ilch_Config $config)
	{
		$dbClass = 'Ilch_Database_'.$config->getConfig('dbEngine');
		$db = new $dbClass();
		$db->connect($config->getConfig('dbHost'), $config->getConfig('dbUser'), $config->getConfig('dbPassword'));
		$db->setDatabase($config->getConfig('dbName'));
		$db->setPrefix($config->getConfig('dbPrefix'));
		
		return $db;
	}

	/**
	 * Gets database adapter by engine name.
	 *
	 * @param string $engine
	 * @return Ilch_Database_*
	 */
	public function getInstanceByEngine($engine)
	{
		$dbClass = 'Ilch_Database_'.$engine;
		$db = new $dbClass();

		return $db;
	}
}