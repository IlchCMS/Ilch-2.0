<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers\Admin;
use Ilch\Validation;

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
            $validation = Validation::create($this->getRequest()->getPost(), [
                'slider' => 'required|numeric|integer|min:0|max:1',
                'boxSliderMode' => 'required',
                'boxSliderHeight' => 'required|numeric|integer|min:0',
                'boxSliderSpeed' => 'required|numeric|integer|min:0'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('partners_slider', $this->getRequest()->getPost('slider'));
                $this->getConfig()->set('partners_slider_mode', $this->getRequest()->getPost('boxSliderMode'));
                $this->getConfig()->set('partners_box_height', $this->getRequest()->getPost('boxSliderHeight'));
                $this->getConfig()->set('partners_slider_speed', $this->getRequest()->getPost('boxSliderSpeed'));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'));
        $this->getView()->set('boxSliderMode', $this->getConfig()->get('partners_slider_mode'));
        $this->getView()->set('boxSliderHeight', $this->getConfig()->get('partners_box_height'));
        $this->getView()->set('boxSliderSpeed', $this->getConfig()->get('partners_slider_speed'));
    }
}
