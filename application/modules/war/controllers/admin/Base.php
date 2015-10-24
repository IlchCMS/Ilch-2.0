<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

class Base extends \Ilch\Controller\Admin
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $active = array();

        foreach(array('group', 'index', 'enemy') as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuWars',
            array
            (
                array
                (
                    'name' => 'menuWars',
                    'active' => $active['index'],
                    'icon' => 'fa fa-shield',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuEnemy',
                    'active' => $active['enemy'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'enemy', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuGroups',
                    'active' => $active['group'],
                    'icon' => 'fa fa-group',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'group', 'action' => 'index'))
                ),
            )
        );
    }
}
