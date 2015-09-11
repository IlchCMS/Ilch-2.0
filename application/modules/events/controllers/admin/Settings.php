<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuEvents',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'menuSettings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuSettings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('event_height', $this->getRequest()->getPost('event_height'));
            $this->getConfig()->set('event_width', $this->getRequest()->getPost('event_width'));
            $this->getConfig()->set('event_size', $this->getRequest()->getPost('event_size'));
            $this->getConfig()->set('event_filetypes', $this->getRequest()->getPost('event_filetypes'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('event_height', $this->getConfig()->get('event_height'));
        $this->getView()->set('event_width', $this->getConfig()->get('event_width'));
        $this->getView()->set('event_size', $this->getConfig()->get('event_size'));
        $this->getView()->set('event_filetypes', $this->getConfig()->get('event_filetypes'));
    }
}
