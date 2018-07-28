<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\AuthToken as AuthTokenModel;

class BeforeControllerLoad
{
    /**
     * Checks if the user has enought rights to access the requested page.
     *
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['router'], $pluginData['config'])) {
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

                    $pluginData['controller']->redirect('');
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

        $request = $pluginData['request'];

        if (!$userId) {
            if ($request->getModuleName() == 'user' && !in_array($request->getControllerName(), ['index', 'login', 'regist'])) {
                $pluginData['controller']->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
            }
        }

        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($userId);

        if (!is_object($user)) {
            // Happens rarely, for example if a user id is saved in the session before reinstalling and the cms got just installed.
            return;
        }

        if ($user->isAdmin()) {
            /*
             * Administrator group should have sight on everything, return here.
             */
            return;
        }

        if ($request->isAdmin() && !$user->isAdmin()) {
            /*
             * Not admins have only access to modules.
             */
            if ($request->getModuleName() == 'admin' && !in_array($request->getControllerName(), ['index', 'login'])) {
                $pluginData['controller']->redirect(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }

            /*
             * Check if user has right for this module.
             */
            if (!$user->hasAccess('module_'.$request->getModuleName()) && $request->getModuleName() !== 'admin') {
                $pluginData['controller']->redirect(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }
        }
    }
}
