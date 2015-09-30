<?php
/**
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
     * @param  \Ilch\Config\File $config
     * @return \Ilch\Database\*|boolean The database object or false if config is not set.
     */
    public function getInstanceByConfig(\Ilch\Config\File $config)
    {
        foreach (array('dbEngine', 'dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $configKey) {
            $dbData[$configKey] = $config->get($configKey);
        }

        $dbClass = '\\Ilch\\Database\\'.$dbData['dbEngine'];
        $db = new $dbClass();
        $hostParts = explode(':', $dbData['dbHost']);
        $port = null;

        if (!empty($hostParts[1])) {
            $port = $hostParts[1];
        }

        $db->connect(reset($hostParts), $dbData['dbUser'], $dbData['dbPassword'], $port);
        $db->setDatabase($dbData['dbName']);
        $db->setPrefix($dbData['dbPrefix']);

        return $db;
    }

    /**
     * Gets database adapter by engine name.
     *
     * @param  string           $engine
     * @return \Ilch\Database\*
     */
    public function getInstanceByEngine($engine)
    {
        $engine = '\\Ilch\\Database\\'.$engine;
        $db = new $engine();

        return $db;
    }
}
