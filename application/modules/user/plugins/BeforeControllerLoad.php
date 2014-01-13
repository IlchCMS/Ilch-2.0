<?php
/**
 * Holds User_BeforeControllerLoadPlugin.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Plugins;
use User\Mappers\User as UserMapper;
use User\Mappers\Group as GroupMapper;
use Admin\Mappers\Module as ModuleMapper;
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
        $groups = $user->getGroups();
        $groupMapper = new GroupMapper();
        $request = $pluginData['request'];
        $accessGranted = false;
        $moduleMapper = new ModuleMapper();
        $modules = $moduleMapper->getModules();

        if($user->hasGroup(1)) {
            /*
             * Administrator group should have sight on everything, return here.
             */
            return;
        }

        if($request->isAdmin()) {
            if(!in_array($request->getModuleName(), array('admin'))) {
                $requestedModuleKey = null;

                foreach($modules as $module) {
                    if($module->getKey() == $request->getModuleName()) {
                        $requestedModuleKey = $module->getKey();
                        break;
                    }
                }

                if(!$user->hasAccess('module_'.$requestedModuleKey)) {
                    $pluginData['controller']->redirect(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'));
                }
            }
        }
    }
}
