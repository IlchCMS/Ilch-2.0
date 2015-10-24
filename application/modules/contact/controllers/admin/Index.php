<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Controllers\Admin;

use Modules\Contact\Mappers\Receiver as ReceiverMapper;
use Modules\Contact\Models\Receiver as ReceiverModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuReceiver',
            array
            (
                array
                (
                    'name' => 'menuReceivers',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'add',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuReceiver'), array('action' => 'index'));

        $receiverMapper = new ReceiverMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_receivers')) {
            foreach($this->getRequest()->getPost('check_receivers') as $receiveId) {
                $receiverMapper->delete($receiveId);
            }
        }

        $receivers = $receiverMapper->getReceivers();
        $this->getView()->set('receivers', $receivers);
    }

    public function deleteAction()
    {
        if($this->getRequest()->isSecure()) {
            $receiverMapper = new ReceiverMapper();
            $receiverMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $receiverMapper = new ReceiverMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuReceiver'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('edit'), array('action' => 'treat', 'id' => $this->getRequest()->getParam('id')));

            $this->getView()->set('receiver', $receiverMapper->getReceiverById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuReceiver'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));
        }

        if ($this->getRequest()->isPost()) {
            $model = new ReceiverModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setName($this->getRequest()->getPost('name'));
            $model->setEmail($this->getRequest()->getPost('email'));

            $receiverMapper->save($model);
            $this->redirect(array('action' => 'index'));
        }
    }
}
