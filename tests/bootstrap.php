<?php
/**
 * Bootstrap file for the PHPUnit implementation.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/*
 * Defining constants from the main ilch "index.php" modified for the tests.
 */
define('ACCESS', 1);
define('VERSION', '2.0');
define('APPLICATION_PATH', __DIR__.'/../application');
define('CONFIG_PATH', __DIR__.'/../');
define('BASE_URL', 'http://localhost/ilch');
define('STATIC_URL', BASE_URL);

/*
 * Initializing the autoloading for the application classes and requires the IlchTestCase.
 */
require_once APPLICATION_PATH.'/libraries/ilch/Functions.php';
require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';
require_once __DIR__.'/IlchTestCase.php';