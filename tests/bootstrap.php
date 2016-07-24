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
define('CONFIG_PATH', __DIR__);
define('SERVER_TIMEZONE', 'Europe/Berlin');

chdir(__DIR__);
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);

$vendorAutoloadFile = __DIR__ . '/../development/vendor/autoload.php';
if (file_exists($vendorAutoloadFile)) {
    require $vendorAutoloadFile;
}

/*
 * Initializing the autoloading for the application classes and for custom
 * PHPUnit Classes.
 */
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new \Ilch\Loader();
