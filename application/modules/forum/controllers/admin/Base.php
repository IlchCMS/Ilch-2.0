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

        foreach (['index'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
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
                ]
            ]
        );
    }
}
