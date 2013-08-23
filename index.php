<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

define('ACCESS', 1);
define('VERSION', '2.0');
define('APPLICATION_PATH', __DIR__.'/application');
define('CONFIG_PATH', '/');
define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].str_replace(array('/index.php', 'index.php'), '', $_SERVER['PHP_SELF']));
define('STATIC_URL', BASE_URL);
define('DB_ENGINE', 'Mysql');

require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';
require_once APPLICATION_PATH.'/libraries/ilch/Functions.php';

Ilch_Registry::set('startTime', microtime(true));

$page = new Ilch_Page();
$page->loadConfig();
$page->loadCms();