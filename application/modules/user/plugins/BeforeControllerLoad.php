<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\User\Mappers\User as UserMapper;

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

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $request = $pluginData['request'];

        if(!$userId) {
            if ($request->getModuleName() == 'user' && !in_array($request->getControllerName(), array('index', 'login', 'regist'))) {
                $pluginData['controller']->redirect(array('module' => 'user', 'controller' => 'login', 'action' => 'index'));
            }
        }

        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($userId);

        if(!is_object($user)) {
            // Happens rarely, for example if a user id is saved in the session before reinstalling and the cms got just installed.
            return;
        }

        if($user->isAdmin()) {
            /*
             * Administrator group should have sight on everything, return here.
             */
            return;
        }

        if($request->isAdmin() && !$user->isAdmin()) {
            /*
             * Not admins have only access to modules.
             */
            if ($request->getModuleName() == 'admin' && !in_array($request->getControllerName(), array('index', 'login'))) {
                $pluginData['controller']->redirect(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'));
            }

            /*
             * Check if user has right for this module.
             */
            if(!$user->hasAccess('module_'.$request->getModuleName()) && $request->getModuleName() !== 'admin') {
                $pluginData['controller']->redirect(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'));
            }
        }
    }
}
