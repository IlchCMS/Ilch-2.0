<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

/**
 * Handles the init for the user module.
 */
class Base extends \Ilch\Controller\Admin
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $active = [];

        foreach (['group', 'index', 'access', 'settings', 'profilefields'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuUser',
            [
                [
                    'name' => 'menuUser',
                    'active' => $active['index'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ],
                [
                    'name' => 'menuGroup',
                    'active' => $active['group'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
                ],
                [
                    'name' => 'menuAccess',
                    'active' => $active['access'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'access', 'action' => 'index'])
                ],
                [
                    'name' => 'menuSettings',
                    'active' => $active['settings'],
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
                ],
                [
                    'name' => 'menuProfileFields',
                    'active' => $active['profilefields'],
                    'icon' => 'fa fa-th-list',
                    'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index'])
                ]
            ]
        );
    }
}