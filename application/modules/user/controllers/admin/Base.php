<?php
/**
 * Holds the class Index.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers\Admin;

defined('ACCESS') or die('no direct access');

/**
 * Handles the init for the user module.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Base extends \Ilch\Controller\Admin
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $active = array();

        foreach(array('group', 'user', 'access') as $controllerName) {
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
                    'active' => $active['user'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuGroup',
                    'active' => $active['group'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'group', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuAccess',
                    'active' => $active['access'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'access', 'action' => 'index'))
                ),
            )
        );
    }
}