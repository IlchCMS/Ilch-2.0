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
	 * @param Ilch_Config_File $config
	 * @return Ilch_Database_*
	 */
	public function getInstanceByConfig(Ilch_Config_File $config)
	{
		$dbClass = 'Ilch_Database_'.$config->get('dbEngine');
		$db = new $dbClass();
		$db->connect($config->get('dbHost'), $config->get('dbUser'), $config->get('dbPassword'));
		$db->setDatabase($config->get('dbName'));
		$db->setPrefix($config->get('dbPrefix'));
		
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