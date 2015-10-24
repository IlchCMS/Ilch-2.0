<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Plugins;

use Modules\User\Mappers\User as UserMapper;

/**
 * Does admin operations before the controller loads.
 */
class BeforeControllerLoad
{
    /**
     * Redirects the user to the admin login page, if the user is not logged in, yet.
     *
     * If the user is logged in already redirect the user to the Admincenter.
     *
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];

        if (isset($pluginData['config'])) {
            $config = $pluginData['config'];
            $userId = null;

            if (isset($_SESSION['user_id'])) {
                $userId = (int) $_SESSION['user_id'];
            }

            $userMapper = new UserMapper();
            $translator = new \Ilch\Translator();
            $user = $userMapper->getUserById($userId);

            if ($config->get('maintenance_mode') && !$request->isAdmin()) {
                if (empty($user)) {
                    $pluginData['layout']->setFile('modules/admin/layouts/maintenance');
                } else {
                    if (!$user->isAdmin()) {
                        $pluginData['layout']->setFile('modules/admin/layouts/maintenance');
                    }
                }
                $_SESSION['messages'][] = array('text' => $translator->trans('siteMaintenanceMode'), 'type' => 'danger');
            }
        }

        if ($request->isAdmin() && $request->getControllerName() !== 'login' && !\Ilch\Registry::get('user')) {
            /*
             * User is not logged in yet but wants to go to the admincenter, redirect him to the login.
             */
            $pluginData['controller']->redirect(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'));
        } elseif ($request->getModuleName() === 'admin' && $request->getControllerName() === 'login' && $request->getActionName() !== 'logout' && \Ilch\Registry::get('user')) {
            /*
             * User is logged in but wants to go to the login, redirect him to the admincenter.
             */
            $pluginData['controller']->redirect(array('module' => 'admin', 'controller' => 'index', 'action' => 'index'));
        }
    }
}
