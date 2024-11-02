<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Plugins;

use Ilch\Database\Exception;
use Ilch\Redirect;
use Ilch\Registry;
use Modules\Statistic\Mappers\Statistic;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Service\Remember as RememberMe;

class AfterDatabaseLoad
{
    /**
     * Checks if a user id was given in the request and sets the user.
     *
     * If no user id is given a default user will be created.
     *
     * @param array $pluginData
     * @throws Exception
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['config'])) {
            return;
        }

        $userId = null;

        if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
            $rememberMe = new RememberMe();
            $rememberMe->reauthenticate();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $mapper = new UserMapper();
        $user = ($userId) ? $mapper->getUserById($userId) : null;

        Registry::set('user', $user);

        // Check if user is locked out or deleted. If that is the case log him out.
        if ($userId && !$user || \is_object($user) && $user->getLocked()) {
            // Delete remember cookie if it exists.
            if (!empty($_COOKIE['remember'])) {
                setcookieIlch('remember', '', strtotime('-1 hours'));
            }

            // Unset all of the session variables and delete user from registry.
            $_SESSION = [];
            Registry::remove('user');

            // Delete session cookie.
            if (ini_get('session.use_cookies')) {
                setcookieIlch(session_name(), '', strtotime('-12 hours'));
            }

            session_destroy();

            $Redirect = new Redirect($pluginData['request'], $pluginData['translator']);
            $Redirect->to($pluginData['request']->getArray());
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '128.0.0.1';
        }

        if (empty($_SERVER['PATH_INFO']) || strpos($_SERVER['PATH_INFO'], 'admin', 1)) {
            $site = '';
        } else {
            $site = $_SERVER['PATH_INFO'];
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            $referer = '';
        } else {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = '';
        } else {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        $request = $pluginData['request'];

        if ($request->getParam('language')) {
            $_SESSION['language'] = $request->getParam('language');
            $pluginData['translator']->setLocale($_SESSION['language']);
            $Redirect = new Redirect($request, $pluginData['translator']);
            $Redirect->to($request->unsetParam('language')->getArray());
        }

        if ($request->getParam('ilch_layout')) {
            $_SESSION['layout'] = $pluginData['request']->getParam('ilch_layout');
            $Redirect = new Redirect($request, $pluginData['translator']);
            $Redirect->to($request->unsetParam('ilch_layout')->getArray());
        }

        $sessionId = session_id();

        // Ignore activity within the admincenter and the ajax requests for new messages or friend requests. Don't save
        // the visit either if the session id is an empty string. This is the case for a just deleted or locked user.
        if (!$request->isAdmin() && !strpos($site, 'user/ajax/checknewmessage') && !strpos($site, 'user/ajax/checknewfriendrequests') && $sessionId) {
            $statisticMapper = new Statistic();
            $statisticMapper->saveVisit(['user_id' => $userId, 'session_id' => $sessionId, 'site' => $site, 'referer' => $referer, 'os' => $statisticMapper->getOS('1'), 'os_version' => $statisticMapper->getOS('', '1'), 'browser' => $statisticMapper->getBrowser('1'), 'browser_version' => $statisticMapper->getBrowser(), 'ip' => $ip, 'lang' => $lang]);
        }

        $pluginData['translator']->setLocale($pluginData['config']->get('locale') ?? '');

        if (!empty($_SESSION['language'])) {
            $pluginData['translator']->setLocale($_SESSION['language']);
        }
    }
}
