<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Base extends \Ilch\Controller\Admin
{
    public function init()
    {
        $active = array();

        foreach(array('index', 'category') as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuLinks',
            array
            (
                array
                (
                    'name' => 'menuLinks',
                    'active' => $active['index'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuCategory',
                    'active' => $active['category'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'category', 'action' => 'index'))
                ),
            )
        );
    }
}