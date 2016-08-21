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

        $post = [
            'slider' => '',
            'boxSliderHeight' => '',
            'boxSliderSpeed' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'slider' => $this->getRequest()->getPost('slider'),
                'boxSliderHeight' => $this->getRequest()->getPost('boxSliderHeight'),
                'boxSliderSpeed' => $this->getRequest()->getPost('boxSliderSpeed')
            ];

            $validation = Validation::create($post, [
                'slider' => 'required|numeric|integer|min:0|max:1',
                'boxSliderHeight' => 'required|numeric|integer|min:0',
                'boxSliderSpeed' => 'required|numeric|integer|min:0'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('partners_slider', $post['slider']);
                $this->getConfig()->set('partners_box_height', $post['boxSliderHeight']);
                $this->getConfig()->set('partners_slider_speed', $post['boxSliderSpeed']);
                $this->addMessage('saveSuccess');
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'));
        $this->getView()->set('boxSliderHeight', $this->getConfig()->get('partners_box_height'));
        $this->getView()->set('boxSliderSpeed', $this->getConfig()->get('partners_slider_speed'));
    }
}
