<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Plugin
{
	/**
	 * @var array
	 */
	protected $_detectedPlugins;

	/**
	 * Data hold by for passing to the plugins.
	 *
	 * @var Array
	 */
	protected $_pluginData = array();

	/**
	 * Searching for plugins.
	 */
	public function detectPlugins()
	{
		foreach(glob(APPLICATION_PATH.'/plugins/*/*') as $pluginPath)
		{
			$pluginName = str_replace('.php', '', basename($pluginPath));
			$pluginPathParts = explode('/', $pluginPath);
			$pluginPathPartsCount = count($pluginPathParts);
			$this->_detectedPlugins[$pluginName][] = $pluginPathParts[$pluginPathPartsCount-2];
		}
	}

	/**
	 * Execute all plugins with the given name.
	 *
	 * @param string $pluginName
	 */
	public function execute($pluginName)
	{
		if(!isset($this->_detectedPlugins[$pluginName]))
		{
			return;
		}

		foreach($this->_detectedPlugins[$pluginName] as $module)
		{
			$pluginClass = $module.'_'.$pluginName.'Plugin';
			$plugin = new $pluginClass($this->_pluginData);
		}
	}

	/**
	 * Adds data for the plugins.
	 *
	 * If data with the key already exists, it will get overwritten.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function addPluginData($key, $value)
	{
		$this->_pluginData[$key] = $value;
	}
}