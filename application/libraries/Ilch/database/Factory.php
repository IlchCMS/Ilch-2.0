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
		$dbClass = '\\Ilch\\Database\\'.$config->get('dbEngine');
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
	 * @return \Ilch\Database\*
	 */
	public function getInstanceByEngine($engine)
	{
		$engine = '\\Ilch\\Database\\'.$engine;
		$db = new $engine();
		return $db;
	}
}