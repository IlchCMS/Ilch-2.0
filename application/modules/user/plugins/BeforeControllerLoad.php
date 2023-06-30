<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\User\Mappers\User as UserMapper;

class BeforeControllerLoad
{
    /**
     * Checks if the user has enough rights to access the requested page.
     *
     * @param array $pluginData
     * @throws \Ilch\Database\Exception
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['router'], $pluginData['config'], $pluginData['accesses'])) {
            return;
        }

        $userId = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $request = $pluginData['request'];

        if (!$userId && $request->getModuleName() === 'user' && !\in_array($request->getControllerName(), ['index', 'login', 'regist'])) {
            $pluginData['controller']->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
        }

        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($userId);

        if (!\is_object($user)) {
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
            if ($request->getModuleName() === 'admin' && !\in_array($request->getControllerName(), ['index', 'login', 'page', 'boxes'])) {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }

            if ($request->getModuleName() === 'admin' && $request->getControllerName() === 'page' && !$pluginData['accesses']->hasAccess('Admin', null, $pluginData['accesses']::TYPE_PAGE)) {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }
            if ($request->getModuleName() === 'admin' && $request->getControllerName() === 'boxes' && !$pluginData['accesses']->hasAccess('Admin', null, $pluginData['accesses']::TYPE_BOX)) {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }
            if ($request->getModuleName() === 'article' && !$pluginData['accesses']->hasAccess('Admin', null, $pluginData['accesses']::TYPE_ARTICLE) && !$user->hasAccess('module_article')) {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }

            /*
             * Check if user has right for this module.
             */
            if (!$user->hasAccess('module_' . $request->getModuleName()) && $request->getModuleName() !== 'admin' && $request->getModuleName() !== 'article') {
                $pluginData['controller']->redirect()->withMessage('noRights', 'danger')->to(['module' => 'admin', 'controller' => 'index', 'action' => 'index']);
            }
        }
    }
}
