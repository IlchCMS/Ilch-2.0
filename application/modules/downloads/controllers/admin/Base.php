<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

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

        foreach(['index', 'downloads', 'file'] as $controllerName) {
            $active[$controllerName] = (boolean)($this->getRequest()->getControllerName() == $controllerName);
        }

        $this->getLayout()->addMenu
        (
            'menuDownloads',
            [
                [
                    'name' => 'menuDownloads',
                    'active' => $active['index'] or $active['downloads'] or $active['file'],
                    'icon' => 'fa fa-th',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ]
            ]
        );
    }
}
