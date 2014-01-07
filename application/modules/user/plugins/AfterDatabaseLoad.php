<?php
/**
 * Holds User_AfterDatabaseLoadPlugin.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Plugins;
use User\Mappers\User as UserMapper;
defined('ACCESS') or die('no direct access');

/**
 * Does user operations before the controller loads.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
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
