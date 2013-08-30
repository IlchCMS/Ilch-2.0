<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

define('ACCESS', 1);
define('VERSION', '2.0');
define('APPLICATION_PATH', __DIR__.'/application');
define('CONFIG_PATH', __DIR__);
define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].str_replace(array('/index.php', 'index.php'), '', $_SERVER['PHP_SELF']));
define('STATIC_URL', BASE_URL);

require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';

Ilch_Registry::set('startTime', microtime(true));

$config = new Ilch_Config();
$page = new Ilch_Page();

if(file_exists(CONFIG_PATH.'/config.php'))
{
	$config->loadConfigFromFile(CONFIG_PATH.'/config.php');
	$page->setInstalled(true);
}

if($config->getConfig('debugModus') === false)
{
	@ini_set('display_errors', 'off');
	error_reporting(0);
}
else
{
	@ini_set('display_errors', 'on');
	error_reporting(-1);
}

session_start();
$page->loadCms($config);