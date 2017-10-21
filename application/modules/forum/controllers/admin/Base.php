<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

/**
 * Handles the init for the forum module.
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
        $active = [];

        foreach (['index', 'settings', 'ranks'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $active['ranks_add'] = (boolean)($active['ranks'] && $this->getRequest()->getActionName() == 'treat');
        if ($active['ranks_add']) {
            $active['ranks'] = false;
        }

        $this->getLayout()->addMenu
        (
            'forum',
            [
                [
                    'name' => 'forum',
                    'active' => $active['index'],
                    'icon' => 'fa fa-th',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ],
                [
                    'name' => 'menuRanks',
                    'active' => $active['ranks'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index']),
                    [
                        'name' => 'add',
                        'active' => $active['ranks_add'],
                        'icon' => 'fa fa-plus-circle',
                        'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'treat'])
                    ]
                ],
                [
                    'name' => 'menuSettings',
                    'active' => $active['settings'],
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
                ]
            ]
        );
    }
}
