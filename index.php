<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

@ini_set('display_errors', 'on');
error_reporting(-1);

session_start();
header('Content-Type: text/html; charset=utf-8');
$serverTimeZone = date_default_timezone_get();
date_default_timezone_set('UTC');

define('ACCESS', 1);
define('VERSION', '1.0');
define('SERVER_TIMEZONE', $serverTimeZone);
define('APPLICATION_PATH', __DIR__.'/application');
define('CONFIG_PATH', APPLICATION_PATH.'/../');

$rewriteBaseParts = $_SERVER['PHP_SELF'];
$rewriteBaseParts = explode('index.php', $rewriteBaseParts);
$rewriteBaseParts = rtrim(reset($rewriteBaseParts), '/');

define('REWRITE_BASE', $rewriteBaseParts);
define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].REWRITE_BASE);
define('STATIC_URL', BASE_URL);

require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';

Ilch_Registry::set('startTime', microtime(true));

$page = new Ilch_Page();
$page->loadCms();
$page->loadPage();