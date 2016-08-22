<?php
/**
 * @copyright Ilch 2.0
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

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_receivers')) {
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

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

            $this->getView()->set('receiver', $receiverMapper->getReceiverById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'name' => '',
            'email' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'email' => $this->getRequest()->getPost('email')
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $model = new ReceiverModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setName($post['name']);
                $model->setEmail($post['email']);
                $receiverMapper->save($model);

                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }
}
