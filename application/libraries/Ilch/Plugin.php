<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Plugin
{
    /**
     * @var array
     */
    protected $detectedPlugins;

    /**
     * Data hold by for passing to the plugins.
     *
     * @var Array
     */
    protected $pluginData = array();

    /**
     * Searching for plugins.
     */
    public function detectPlugins()
    {
        foreach (glob(APPLICATION_PATH.'/modules/*/plugins/*') as $pluginPath) {
            $pluginName = str_replace('.php', '', basename($pluginPath));
            $pluginPathParts = explode('/', $pluginPath);
            $pluginPathPartsCount = count($pluginPathParts);
            $this->detectedPlugins[$pluginName][] = $pluginPathParts[$pluginPathPartsCount-3];
        }
    }

    /**
     * Execute all plugins with the given name.
     *
     * @param string $pluginName
     */
    public function execute($pluginName)
    {
        if (!isset($this->detectedPlugins[$pluginName])) {
            return;
        }

        foreach ($this->detectedPlugins[$pluginName] as $module) {
            $pluginClass = '\\Modules\\'.ucfirst($module).'\\Plugins\\'.$pluginName.'';
            new $pluginClass($this->pluginData);
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
        $this->pluginData[$key] = $value;
    }
}
