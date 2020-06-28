<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database;

use Ilch\DebugBar;

class Factory
{
    /**
     * Gets database adapter by config.
     *
     * @param  \Ilch\Config\File $config
     * @return \Ilch\Database\Mysql The database object
     * @throws \RuntimeException
     */
    public function getInstanceByConfig(\Ilch\Config\File $config)
    {
        foreach (['dbEngine', 'dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix'] as $configKey) {
            $dbData[$configKey] = $config->get($configKey);
        }

        $dbClass = '\\Ilch\\Database\\'.$dbData['dbEngine'];

        $addDebugCollector = false;
        if (DebugBar::isInitialized()) {
            $debugDbClass = $dbClass . 'Debug';
            if (class_exists($debugDbClass)) {
                $db = new $debugDbClass(DebugBar::getInstance()->getCollector('exceptions'));
                $addDebugCollector = true;
            }
        }

        if (!isset($db)) {
            if (!class_exists($dbClass)) {
                throw new \RuntimeException('Invalid database engine ' . $dbData['dbEngine']);
            }
            $db = new $dbClass();
        }
        /** @var Mysql|MysqlDebug $db */

        $hostParts = explode(':', $dbData['dbHost']);
        $port = null;

        if (!empty($hostParts[1])) {
            $port = $hostParts[1];
        }

        $db->connect(reset($hostParts), $dbData['dbUser'], $dbData['dbPassword'], $port);
        if (!$db->setDatabase($dbData['dbName'])) {
            throw new \RuntimeException('Unable to select database ' . $dbData['dbName']);
        }
        $db->setPrefix($dbData['dbPrefix']);

        if ($addDebugCollector) {
            DebugBar::getInstance()->addCollector(new DebugBar\DataCollector\MysqlCollector($db));
        }

        return $db;
    }

    /**
     * Gets database adapter by engine name.
     *
     * @param  string           $engine
     * @return \Ilch\Database\MySql
     */
    public function getInstanceByEngine($engine)
    {
        $engine = '\\Ilch\\Database\\'.$engine;
        return new $engine();
    }
}
