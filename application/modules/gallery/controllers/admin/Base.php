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
        $active = array();

        foreach(array('index', 'gallery', 'image') as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuGallery',
            array
            (
                array
                (
                    'name' => 'menuGallery',
                    'active' => $active['index'] or $active['gallery'] or $active['image'],
                    'icon' => 'fa fa-th',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                )
            )
        );
    }
}
