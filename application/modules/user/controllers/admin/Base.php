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
        $active = array('group' => false, 'user' => false);

        if($this->getRequest()->getControllerName() == 'group')
        {
            $active['group'] = true;
        }
        else
        {
            $active['user'] = true;
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
            )
        );
    }
}