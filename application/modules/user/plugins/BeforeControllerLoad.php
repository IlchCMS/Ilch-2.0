<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Admin\Mappers\Module as ModuleMapper;
defined('ACCESS') or die('no direct access');

/**
 * Does user operations before the controller loads.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
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

        if(!$userId) {
            return;
        }

        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($userId);

        if(!is_object($user)) {
            // Happens rarely, for example if a user id is saved in the session before reinstalling and the cms got just installed.
            return;
        }

        $request = $pluginData['request'];

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
