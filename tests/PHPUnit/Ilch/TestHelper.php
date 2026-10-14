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
     * Creates a config object from the config file, applies the given
     * test-specific overrides and injects it into the registry.
     *
     * The object is rebuilt on every call, so overrides from one test class
     * never leak into another and the registry never receives a null config.
     *
     * @param array $configData
     */
    public static function setConfigInRegistry(array $configData): void
    {
        $configFile = CONFIG_PATH . '/config.php';

        if (!file_exists($configFile)) {
            // Nothing to load. Leave the registry untouched;
            // DatabaseTestCase::setUpBeforeClass() reports a missing config.
            return;
        }

        $config = new Config();
        $config->loadConfigFromFile($configFile);

        foreach ($configData as $configKey => $configValue) {
            $config->set($configKey, $configValue);
        }

        Registry::remove('config');
        Registry::set('config', $config);
    }
}
