<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

if (!version_compare(PHP_VERSION, '7.3', '>=')) {
    die('Ilch CMS 2 needs at least php version 7.3');
}

@ini_set('display_errors', 'on');
error_reporting(E_ALL);

$isHttps = $_SERVER['HTTPS'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
$isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);

define('ISHTTPSPAGE', $isHttps);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['SERVER_NAME'],
    'samesite' => 'Lax',
    'secure' => ISHTTPSPAGE,
    'httponly' => true,
]);
session_start();
header('Content-Type: text/html; charset=utf-8');
$serverTimeZone = @date_default_timezone_get();
date_default_timezone_set('UTC');

define('VERSION', '2.1.52');
define('SERVER_TIMEZONE', $serverTimeZone);
define('DEFAULT_MODULE', 'page');
define('DEFAULT_LAYOUT', 'index');
define('DEBUG_MODE', true);

// Path could not be under root.
define('ROOT_PATH', __DIR__);
define('APPLICATION_PATH', __DIR__ . '/application');
define('CONFIG_PATH', APPLICATION_PATH);

$rewriteBaseParts = explode('index.php', str_replace('Index.php', 'index.php', $_SERVER['PHP_SELF']));
$rewriteBaseParts = rtrim(reset($rewriteBaseParts), '/');

define('REWRITE_BASE', $rewriteBaseParts);
$protocol = ISHTTPSPAGE ? 'https' : 'http';
define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . REWRITE_BASE);

//Get Platform-Version from User-Agent Client Hints
header("Accept-CH: Sec-CH-UA, Sec-CH-UA-Platform-Version");

// register autoloaders
require ROOT_PATH . '/vendor/autoload.php';
$loader = new \Ilch\Loader();

if (DEBUG_MODE) {
    \Ilch\DebugBar::init();
}

\Ilch\Registry::set('startTime', microtime(true));

try {
    $page = new \Ilch\Page();
    $page->loadCms();
    $page->loadPage();
} catch (Exception $ex) {
    print 'An unexpected error occurred: <pre>' . $ex->getMessage() . '</pre>';
}
