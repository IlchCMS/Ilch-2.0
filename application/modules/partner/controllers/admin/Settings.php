<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers\Admin;

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
            'menuPartner',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('partners_slider', $this->getRequest()->getPost('slider'));
            $this->getConfig()->set('partners_box_height', $this->getRequest()->getPost('boxHeight'));
            $this->getConfig()->set('partners_slider_speed', $this->getRequest()->getPost('sliderSpeed'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'));
        $this->getView()->set('boxHeight', $this->getConfig()->get('partners_box_height'));
        $this->getView()->set('sliderSpeed', $this->getConfig()->get('partners_slider_speed'));
    }
}
