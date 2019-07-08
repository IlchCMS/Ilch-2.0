<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\Statistic\Mappers\Statistic;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\AuthToken as AuthTokenModel;

class AfterDatabaseLoad
{
    /**
     * Checks if a user id was given in the request and sets the user.
     *
     * If no user id is given a default user will be created.
     *
     * @param array $pluginData
     * @throws \Ilch\Database\Exception
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['config'])) {
            return;
        }

        $userId = null;

        if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
            list($selector, $authenticator) = explode(':', $_COOKIE['remember']);

            $authTokenMapper = new AuthTokenMapper();
            $authToken = $authTokenMapper->getAuthToken($selector);

            if (!empty($authToken) && strtotime($authToken->getExpires()) >= time()) {
                if (hash_equals($authToken->getToken(), hash('sha256', base64_decode($authenticator)))) {
                    $_SESSION['user_id'] = $authToken->getUserid();
                    // A new token is generated, a new hash for the token is stored over the old record, and a new login cookie is issued to the user.
                    $authTokenModel = new AuthTokenModel();

                    $authTokenModel->setSelector($selector);
                    // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
                    $authenticator = openssl_random_pseudo_bytes(33);
                    // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
                    $authTokenModel->setToken(hash('sha256', $authenticator));
                    $authTokenModel->setUserid($_SESSION['user_id']);
                    $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

                    setcookie('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime( '+30 days' ), '/', $_SERVER['SERVER_NAME'], (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'), true);
                    $authTokenMapper->updateAuthToken($authTokenModel);
                } else {
                    // If the series is present but the token does not match, a theft is assumed.
                    // The user receives a strongly worded warning and all of the user's remembered sessions are deleted.
                    $cookieStolenMapper = new CookieStolenMapper();
                    $cookieStolenMapper->addCookieStolen($authToken->getUserid());
                    $authTokenMapper->deleteAllAuthTokenOfUser($authToken->getUserid());
                }
            }
        }

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $mapper = new UserMapper();
        $user = $mapper->getUserById($userId);

        \Ilch\Registry::set('user', $user);

        // Check if user is locked out. If that is the case log him out.
        if (is_object($user) && $user->getLocked()) {
            if (!empty($_COOKIE['remember'])) {
                setcookie('remember', '', time() - 3600, '/', $_SERVER['SERVER_NAME'], false, false);
            }

            $_SESSION = [];
            \Ilch\Registry::remove('user');

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params["path"],
                    $params["domain"], $params["secure"], $params["httponly"]
                );
            }

            session_destroy();
        }

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = '128.0.0.1';
        }

        if (empty($_SERVER['PATH_INFO']) OR strpos($_SERVER['PATH_INFO'], 'admin', 1)) {
            $site = '';
        } else {
            $site = $_SERVER['PATH_INFO'];
        }

        if (empty($_SERVER["HTTP_REFERER"])) {
            $referer = '';
        }  else {
            $referer = $_SERVER["HTTP_REFERER"];
        }

        if (empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $lang = '';
        } else {
            $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        }

        $request = $pluginData['request'];

        if (!$request->isAdmin()) {
            if ((strpos($site, 'user/ajax/checknewmessage') == false) && (strpos($site, 'user/ajax/checknewfriendrequests') == false)) {
                $statisticMapper = new Statistic();
                $statisticMapper->saveVisit(['user_id' => $userId, 'session_id' => session_id(), 'site' => $site, 'referer' => $referer, 'os' => $statisticMapper->getOS('1'), 'os_version' => $statisticMapper->getOS('', '1'), 'browser' => $statisticMapper->getBrowser('1'), 'browser_version' => $statisticMapper->getBrowser(), 'ip' => $ip, 'lang' => $lang]);
            }
        }

        if ($pluginData['request']->getParam('language')) {
            $_SESSION['language'] = $pluginData['request']->getParam('language');
        }

        if ($pluginData['request']->getParam('ilch_layout')) {
            $_SESSION['layout'] = $pluginData['request']->getParam('ilch_layout');
        }

        $pluginData['translator']->setLocale($pluginData['config']->get('locale'));

        if (!empty($_SESSION['language'])) {
            $pluginData['translator']->setLocale($_SESSION['language']);
        }
    }
}
