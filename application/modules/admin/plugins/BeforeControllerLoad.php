<?php

/**
 * @copyright Ilch 2
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
            $translator->load(APPLICATION_PATH . '/modules/admin/translations');
            $user = ($userId) ? $userMapper->getUserById($userId) : null;

            if ($config->get('maintenance_mode') && !$request->isAdmin()) {
                if ($user === null) {
                    $pluginData['layout']->setFile('modules/admin/layouts/maintenance');
                } elseif (!$user->isAdmin()) {
                    $pluginData['layout']->setFile('modules/admin/layouts/maintenance');
                }
                $_SESSION['messages']['maintenance'] = ['text' => $translator->trans('siteMaintenanceMode'), 'type' => 'danger'];
            }
        }

        $user = \Ilch\Registry::get('user');

        if (!$user & $request->isAdmin() && $request->getControllerName() !== 'login') {
            // User is not logged in yet but wants to go to the admincenter, redirect him to the login.
            $pluginData['controller']->redirect(['module' => 'admin', 'controller' => 'login', 'action' => 'index']);
        } elseif ($user && $request->getModuleName() === 'admin' && $request->getControllerName() === 'login' && $request->getActionName() !== 'logout') {
            // User is logged in but wants to go to the login, redirect him to the admincenter.
            $pluginData['controller']->redirect(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
        } elseif ($user && $request->getModuleName() === 'admin' && $request->getControllerName() !== 'login' && $request->getControllerName() !== 'page' && $request->getActionName() !== 'logout' && !$user->isAdmin()) {
            if (!$pluginData['accesses']->hasAccess('Admin')) {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to([], 'frontend');
            }
        }
    }
}
