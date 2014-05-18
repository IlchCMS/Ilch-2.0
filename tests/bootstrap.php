<?php
/**
 * Bootstrap file for the PHPUnit implementation.
 *
 * @package ilch_phpunit
 */

/*
 * Defining constants from the main ilch "index.php" modified for the tests.
 */
define('ACCESS', 1);
define('VERSION', '2.0');
define('APPLICATION_PATH', __DIR__.'/../application');
define('CONFIG_PATH', APPLICATION_PATH);
define('SERVER_TIMEZONE', 'Europe/Berlin');

$vendorAutoloadFile = __DIR__ . '/../development/vendor/autoload.php';
if (file_exists($vendorAutoloadFile)) {
    require $vendorAutoloadFile;
}

/*
 * Initializing the autoloading for the application classes and for custom
 * PHPUnit Classes.
 */
require_once APPLICATION_PATH.'/libraries/Ilch/Loader.php';
$loader = new \Ilch\Loader();
spl_autoload_register
(
    function ($class) {
        /*
         * Simply replacing all underscores with slashes, routing on from the current
         * dir and appending the ".php" suffix to get the filepath.
         */
        $filepath = __DIR__.'/'.str_replace('_', '/', $class).'.php';

        if (is_file($filepath)) {
            require_once $filepath;
        }
    }
);
