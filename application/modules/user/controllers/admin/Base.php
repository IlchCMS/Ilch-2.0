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
        $active = array();

        foreach(array('group', 'index', 'access', 'settings') as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuUser',
            array
            (
                array
                (
                    'name' => 'menuUser',
                    'active' => $active['index'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuGroup',
                    'active' => $active['group'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'group', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuAccess',
                    'active' => $active['access'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'access', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuSettings',
                    'active' => $active['settings'],
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }
}