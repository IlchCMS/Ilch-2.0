<?php
/**
 * Holds the class Index.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

defined('ACCESS') or die('no direct access');

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

        foreach(array('index', 'gallery') as $controllerName) {
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
                    'active' => $active['index'] or $active['gallery'],
                    'icon' => 'fa fa-th',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                )
            )
        );
    }
}