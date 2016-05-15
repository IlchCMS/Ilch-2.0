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

define('VERSION', '2.0.0');
define('ILCH_SERVER', 'http://www.ilch.de/ilch2');
define('SERVER_TIMEZONE', $serverTimeZone);
define('DEFAULT_MODULE', 'page');
define('DEFAULT_LAYOUT', 'index');

/*
 * Path could not be under root.
 */
define('ROOT_PATH', __DIR__);
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

    if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
        list($selector, $authenticator) = explode(':', $_COOKIE['remember']);

        $authTokenMapper = new Modules\User\Mappers\AuthToken();
        $row = $authTokenMapper->getAuthToken($selector);

        if(!empty($row) && strtotime($row['expires']) >= time()) {
            if (hash_equals($row['token'], hash('sha256', base64_decode($authenticator)))) {
                $_SESSION['user_id'] = $row['userid'];
                // A new token is generated, a new hash for the token is stored over the old record, and a new login cookie is issued to the user.
                $authTokenModel = new Modules\User\Models\AuthToken();

                // 9 bytes of random data (base64 encoded to 12 characters) for the selector.
                // This provides 72 bits of keyspace and therefore 236 bits of collision resistance (birthday attacks)
                $authTokenModel->setSelector(base64_encode(openssl_random_pseudo_bytes(9)));
                // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
                $authenticator = openssl_random_pseudo_bytes(33);
                // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
                $authTokenModel->setToken(hash('sha256', $authenticator));
                $authTokenModel->setUserid($_SESSION['user_id']);
                $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

                setcookie('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime( '+30 days' ), '/', $_SERVER['SERVER_NAME'], false, false);
                $authTokenMapper->updateAuthToken($authTokenModel);
            } else {
                // If the series is present but the token does not match, a theft is assumed.
                // The user receives a strongly worded warning and all of the user's remembered sessions are deleted.
                $authTokenMapper->deleteAllAuthTokenOfUser($row['userid']);
            }
        }
    }

    $page->loadPage();
} catch (Exception $ex) {
    print 'An unexpected error occurred: ' . $ex->getMessage();
}
