<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

/**
 * Handles the init for the gallery module.
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

        foreach (['index', 'gallery', 'image', 'settings'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuGallery',
            [
                [
                    'name' => 'menuGallery',
                    'active' => $active['index'] or $active['gallery'] or $active['image'],
                    'icon' => 'fa fa-th',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ],
                [
                    'name' => 'menuSettings',
                    'active' => $active['settings'],
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
                ]
            ]
        );
    }
}
