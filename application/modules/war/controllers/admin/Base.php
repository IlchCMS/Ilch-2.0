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
        $active = [];

        foreach(['group', 'index', 'enemy'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuWars',
            [
                [
                    'name' => 'menuWars',
                    'active' => $active['index'],
                    'icon' => 'fa fa-shield',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ],
                [
                    'name' => 'menuEnemy',
                    'active' => $active['enemy'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
                ],
                [
                    'name' => 'menuGroups',
                    'active' => $active['group'],
                    'icon' => 'fa fa-group',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
                ],
            ]
        );
    }
}
