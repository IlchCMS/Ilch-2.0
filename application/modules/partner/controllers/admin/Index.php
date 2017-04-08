<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers\Admin;

use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Partner as PartnerModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuPartner',
            $items
        );
    }

    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $partnerId) {
                    $partnerMapper->delete($partnerId);
                }
            }

            if ($this->getRequest()->getPost('action') == 'setfree') {
                foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                    $model = new PartnerModel();
                    $model->setId($entryId);
                    $model->setFree(1);
                    $partnerMapper->save($model);
                }
            }
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $partnerMapper->getEntries(['setfree' => 0]);
        } else {
            $entries = $partnerMapper->getEntries(['setfree' => 1]);
        }

        $this->getView()->set('entries', $entries);
        $this->getView()->set('badge', count($partnerMapper->getEntries(['setfree' => 0])));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $partnerMapper = new PartnerMapper();
            $partnerMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function setfreeAction()
    {
        if ($this->getRequest()->isSecure()) {
            $partnerMapper = new PartnerMapper();

            $model = new PartnerModel();
            $model->setId($this->getRequest()->getParam('id'));
            $model->setFree(1);
            $partnerMapper->save($model);

            $this->addMessage('freeSuccess');
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(['action' => 'index', 'showsetfree' => 1]);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function treatAction() 
    {
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('partner', $partnerMapper->getPartnerById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'name' => '',
            'link' => '',
            'banner' => ''
        ];

        if ($this->getRequest()->isPost()) {

            $banner = trim($this->getRequest()->getPost('banner'));
            if (!empty($banner)) {
                if (substr($banner, 0, 11) == 'application') {
                    $banner = BASE_URL.'/'.$banner;
                }
            }

            $post = [
                'name' => trim($this->getRequest()->getPost('name')),
                'link' => trim($this->getRequest()->getPost('link')),
                'banner' => $banner
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'link' => 'required|url',
                'banner' => 'required|url'
            ]);

            $post['banner'] = trim($this->getRequest()->getPost('banner'));

            if ($validation->isValid()) {
                $model = new PartnerModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setName($post['name']);
                $model->setLink($post['link']);
                $model->setBanner($post['banner']);
                $model->setFree(1);
                $partnerMapper->save($model);

                unset($_SESSION['captcha']);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            unset($_SESSION['captcha']);
        }

        $this->getView()->set('post', $post);
    }
}
