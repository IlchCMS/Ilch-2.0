<?php
/**
 * Holds the class Index.
 *
 * @copyright Ilch Pluto
 * @package ilch
 */

namespace User\Controllers\Admin;

use \Ilch\Registry as Registry;

defined('ACCESS') or die('no direct access');

/**
 * Handles the init for the user module.
 *
 * @copyright Ilch Pluto
 * @package ilch
 */
class Base extends \Ilch\Controller\Admin
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuUser',
            array
            (
                array
                (
                    'name' => 'menuUser',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuGroup',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'group', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewUser',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat', 'id' => 0))
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewGroup',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'group', 'action' => 'treat', 'id' => 0))
            )
        );
    }
}