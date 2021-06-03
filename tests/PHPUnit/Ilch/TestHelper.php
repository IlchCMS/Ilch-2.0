<?php
/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry;
use Ilch\Config\File as Config;

/**
 * Helper class for the PHPUnit execution.
 *
 * @package ilch_phpunit
 */
class TestHelper
{
    /**
     * The config variable which will be set for the tests.
     *
     * @static Static so we can dont have to connect for every test again.
     * @var \Ilch\Config\File
     */
    static private $config = null;

    /**
     * Filling the config object with individual testcase data and injecting it into the registry.
     * @param array $configData
     */
    public static function setConfigInRegistry(array $configData)
    {
        if ((static::$config === null) && !Registry::has('config') && file_exists(CONFIG_PATH . '/config.php')) {
            static::$config = new Config();
            static::$config->loadConfigFromFile(CONFIG_PATH . '/config.php');

            foreach ($configData as $configKey => $configValue) {
                static::$config->set($configKey, $configValue);
            }
        }

        Registry::remove('config');
        Registry::set('config', self::$config);
    }
}
