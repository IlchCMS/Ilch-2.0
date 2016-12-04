<?php
/**
 * @copyright Ilch 2.0
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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
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

        if ($this->getRequest()->getPost('check_privacys')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_privacys') as $privacyId) {
                    $privacyMapper->delete($privacyId);
                }
            }
        }

        $this->getView()->set('privacys', $privacyMapper->getPrivacy());
    }

    public function treatAction() 
    {
        $privacyMapper = new PrivacyMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('privacy', $privacyMapper->getPrivacyById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'show' => 'required|numeric|integer|min:0|max:1',
                'title' => 'required',
                'text' => 'required',
                'url' => 'url'
            ]);

            if ($validation->isValid()) {
                $model = new PrivacyModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setShow($this->getRequest()->getPost('show'));
                $model->setTitle($this->getRequest()->getPost('title'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setUrlTitle($this->getRequest()->getPost('urltitle'))
                    ->setUrl($this->getRequest()->getPost('url'));
                $privacyMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
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
