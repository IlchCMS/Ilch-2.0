<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Contact\Controllers\Admin;

use Modules\Contact\Mappers\Receiver as ReceiverMapper;
use Modules\Contact\Models\Receiver as ReceiverModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuReceivers',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuContact',
            $items
        );
    }

    public function indexAction()
    {
        $receiverMapper = new ReceiverMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_receivers')) {
            foreach ($this->getRequest()->getPost('check_receivers') as $receiveId) {
                $receiverMapper->delete($receiveId);
            }
        }

        $this->getView()->set('receivers', $receiverMapper->getReceivers());
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $receiverMapper = new ReceiverMapper();
            $receiverMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(['action' => 'index']);
    }

    public function treatAction()
    {
        $receiverMapper = new ReceiverMapper();
        $receiverModel = new ReceiverModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

            $receiverModel = $receiverMapper->getReceiverById($this->getRequest()->getParam('id'));
            if (!$receiverModel) {
                $this->redirect()
                    ->withMessage('entryNotFound', 'danger')
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
            $this->getView()->set('receiver', $receiverModel);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required',
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $receiverModel->setName($this->getRequest()->getPost('name'));
                $receiverModel->setEmail($this->getRequest()->getPost('email'));
                $receiverMapper->save($receiverModel);

                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
            }
        }
    }
}
