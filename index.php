<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

if (!version_compare(phpversion(), '5.4.0', '>=')) {
    die('Ilch CMS 2.* needed minimum php version 5.4.0');
}

@ini_set('display_errors', 'on');
error_reporting(E_ALL);

session_start();
header('Content-Type: text/html; charset=utf-8');
$serverTimeZone = @date_default_timezone_get();
date_default_timezone_set('UTC');

define('ACCESS', 1);
define('VERSION', '2.0.0');
define('ILCH_SERVER', 'http://www.ilch.de/ilch2');
define('SERVER_TIMEZONE', $serverTimeZone);
define('DEFAULT_MODULE', 'page');
define('DEFAULT_LAYOUT', 'index');

/*
 * Path could not be under root.
 */
define('APPLICATION_PATH', __DIR__.'/application');
define('CONFIG_PATH', APPLICATION_PATH);

$rewriteBaseParts = explode('index.php', str_replace('Index.php', 'index.php', $_SERVER['PHP_SELF']));
$rewriteBaseParts = rtrim(reset($rewriteBaseParts), '/');

define('REWRITE_BASE', $rewriteBaseParts);
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
define('BASE_URL', $protocol.'://'.$_SERVER['HTTP_HOST'].REWRITE_BASE);


require_once APPLICATION_PATH.'/libraries/Ilch/Loader.php';
$loader = new \Ilch\Loader();
$loader->registNamespace('Thumb');

\Ilch\Registry::set('startTime', microtime(true));

try {
    $page = new \Ilch\Page();
    $page->loadCms();
    $page->loadPage();
} catch (Exception $ex) {
    print 'An unexpected error occurred: ' . $ex->getMessage();
}
