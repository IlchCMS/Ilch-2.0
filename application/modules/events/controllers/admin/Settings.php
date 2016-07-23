<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuEvents',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('event_height', $this->getRequest()->getPost('event_height'));
            $this->getConfig()->set('event_width', $this->getRequest()->getPost('event_width'));
            $this->getConfig()->set('event_size', $this->getRequest()->getPost('event_size'));
            $this->getConfig()->set('event_filetypes', $this->getRequest()->getPost('event_filetypes'));
            $this->getConfig()->set('event_google_api_key', $this->getRequest()->getPost('event_google_api_key'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('event_height', $this->getConfig()->get('event_height'));
        $this->getView()->set('event_width', $this->getConfig()->get('event_width'));
        $this->getView()->set('event_size', $this->getConfig()->get('event_size'));
        $this->getView()->set('event_filetypes', $this->getConfig()->get('event_filetypes'));
        $this->getView()->set('event_google_api_key', $this->getConfig()->get('event_google_api_key'));
    }
}
