<?php
/**
 * Holds class PHPUnit_Ilch_TestHelper.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Helper class for the PHPUnit execution.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class PHPUnit_Ilch_TestHelper
{
    /**
     * The config variable which will be set for the tests.
     *
     * @static Static so we can dont have to connect for every test again.
     * @var Ilch\Config\File
     */
    static private $config = null;

    /**
     * Filling the config object with individual testcase data and injecting it into the registry.
     */
    public function setConfigInRegistry($configData)
    {
        if (self::$config === null) {
            if (!\Ilch\Registry::has('config') && file_exists(CONFIG_PATH.'/config.php')) {
                self::$config = new \Ilch\Config\File();
                self::$config->loadConfigFromFile(CONFIG_PATH.'/config.php');

                foreach ($configData as $configKey => $configValue) {
                    self::$config->set($configKey, $configValue);
                }
            }
        }

        \Ilch\Registry::remove('config');
        \Ilch\Registry::set('config', self::$config);
    }
}