<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\User\Mappers\User as UserMapper;

class AfterDatabaseLoad
{
    /**
     * Checks if a user id was given in the request and sets the user.
     *
     * If no user id is given a default user will be created.
     *
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['config'])) {
            return;
        }

        $userId = null;

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $mapper = new UserMapper();
        $user = $mapper->getUserById($userId);

        \Ilch\Registry::set('user', $user);

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

        $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);

        $statisticMapper = new \Modules\Statistic\Mappers\Statistic();
        $statisticMapper->saveVisit(array('user_id' => $userId, 'site' => $site, 'referer' => $referer, 'os' => $statisticMapper->getOS('1'), 'os_version' => $statisticMapper->getOS('', '1'), 'browser' => $statisticMapper->getBrowser('1'), 'browser_version' => $statisticMapper->getBrowser(), 'ip' => $ip, 'lang' => $lang));

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
