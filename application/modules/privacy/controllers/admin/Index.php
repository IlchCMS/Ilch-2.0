<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Privacy\Controllers\Admin;

use Modules\Privacy\Mappers\Privacy as PrivacyMapper;
use Modules\Privacy\Models\Privacy as PrivacyModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuPrivacy',
            $items
        );
    }

    public function indexAction()
    {
        $privacyMapper = new PrivacyMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_privacys') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_privacys') as $privacyId) {
                $privacyMapper->delete($privacyId);
            }
        }

        if ($this->getRequest()->getPost('saveRules')) {
            foreach ($this->getRequest()->getPost('items') as $i => $id) {
                $privacyMapper->sort($id, $i);
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('privacies', $privacyMapper->getPrivacy());
    }

    public function treatAction()
    {
        $privacyMapper = new PrivacyMapper();

        $model = new PrivacyModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $model = $privacyMapper->getPrivacyById($this->getRequest()->getParam('id'));
            if (!$model) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('privacy', $model);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases(['urltitle' => 'urlTitle']);

            $validationRules = [
                'show' => 'required|numeric|integer|min:0|max:1',
                'title' => 'required',
                'text' => 'required',
                'url' => 'url'
            ];

            if ($this->getRequest()->getPost('urltitle') || $this->getRequest()->getPost('url')) {
                $validationRules['urltitle'] = 'required';
                $validationRules['url'] = 'required|url';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $model->setShow($this->getRequest()->getPost('show'));
                $model->setTitle($this->getRequest()->getPost('title'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setUrlTitle($this->getRequest()->getPost('urltitle'))
                    ->setUrl($this->getRequest()->getPost('url'));
                $privacyMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treat'], ($model->getId() ? ['id' => $model->getId()] : [])));
            }
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $privacyMapper = new PrivacyMapper();
            $privacyMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $privacyMapper = new PrivacyMapper();
            $privacyMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
