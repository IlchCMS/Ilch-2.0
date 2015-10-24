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
        $this->getLayout()->addMenu
        (
            'menuPartner',
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
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'settings',
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
                ->add($this->getTranslator()->trans('menuPartner'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));

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
