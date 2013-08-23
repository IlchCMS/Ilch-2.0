<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch CMS 2.0
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
	    $plugin = new $pluginClass();
	}
    }
}