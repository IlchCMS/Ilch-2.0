<?php
/**
 * Holds class \PHPUnit\Ilch\TestHelper
 *
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

use Ilch\Registry as Registry;
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
     */
    public function setConfigInRegistry($configData)
    {
        if (self::$config === null) {
            if (!Registry::has('config') && file_exists(CONFIG_PATH . '/config.php')) {
                self::$config = new Config();
                self::$config->loadConfigFromFile(CONFIG_PATH . '/config.php');

                foreach ($configData as $configKey => $configValue) {
                    self::$config->set($configKey, $configValue);
                }
            }
        }

        Registry::remove('config');
        Registry::set('config', self::$config);
    }
}