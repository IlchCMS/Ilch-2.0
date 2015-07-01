<?php
/**
 * Matches base controller
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Matches\Controllers\Admin;

class Base extends \Ilch\Controller\Admin
{
    public function init()
    {
        // Matches menu
        $this->getLayout()->addMenu(
            'Matches',
            array(
                array(
                    'name' => 'Verwalten',
                    'active' => $this->getRequest()->getControllerName() == 'index' &&
                                $this->getRequest()->getActionName() == 'index',
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array(
                    'name' => 'Match erstellen',
                    'active' => $this->getRequest()->getControllerName() == 'index' &&
                                $this->getRequest()->getActionName() == 'new',
                    'icon' => 'fa fa-plus',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'new'))
                ),
            )
        );

        // Opponents menu
        $this->getLayout()->addMenu(
            'Opponents',
            array(
                array(
                    'name' => 'Verwalten',
                    'active' => $this->getRequest()->getControllerName() == 'opponents' &&
                                $this->getRequest()->getActionName() == 'index',
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'opponents', 'action' => 'index'))
                ),
                array(
                    'name' => 'Gegner erstellen',
                    'active' => $this->getRequest()->getControllerName() == 'opponents' &&
                                $this->getRequest()->getActionName() == 'new',
                    'icon' => 'fa fa-plus',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'opponents', 'action' => 'new'))
                ),
            )
        );
    }
    public function input($key = null, $default = null)
    {
        return $this->getRequest()->getPost($key, $default);
    }
    public function t($key)
    {
        $args = func_get_args();
        return call_user_func_array(array($this->getTranslator(), 'trans'), $args);
    }
}
