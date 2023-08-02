<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

use Ilch\DebugBar;
use Ilch\Loader;
use Ilch\Page;
use Ilch\Registry;

if (!version_compare(PHP_VERSION, '7.3', '>=')) {
    die('Ilch CMS 2 needs at least php version 7.3');
}

@ini_set('display_errors', 'on');
error_reporting(E_ALL);

define('ISHTTPSPAGE', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'));

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

date_default_timezone_set('UTC');

define('VERSION', '2.1.51');
define('SERVER_TIMEZONE', @date_default_timezone_get());
define('DEFAULT_MODULE', 'page');
define('DEFAULT_LAYOUT', 'index');
define('DEBUG_MODE', true);

// Path could not be under root.
define('ROOT_PATH', __DIR__);
define('APPLICATION_PATH', __DIR__ . '/application');
define('CONFIG_PATH', APPLICATION_PATH);

$rewriteBaseParts = explode('index.php', str_ireplace('Index.php', 'index.php', $_SERVER['PHP_SELF']));
define('REWRITE_BASE', rtrim(reset($rewriteBaseParts), '/'));

$protocol = ISHTTPSPAGE ? 'https' : 'http';
define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . REWRITE_BASE);

//Get Platform-Version from User-Agent Client Hints
header("Accept-CH: Sec-CH-UA, Sec-CH-UA-Platform-Version");

// register autoloaders
require ROOT_PATH . '/vendor/autoload.php';
$loader = new Loader();

if (DEBUG_MODE) {
    DebugBar::init();
}

Registry::set('startTime', microtime(true));

try {
    $page = new Page();
    $page->loadCms();
    $page->loadPage();
} catch (Exception $ex) {
    print 'An unexpected error occurred: <pre>' . $ex->getMessage() . '</pre>';
}
