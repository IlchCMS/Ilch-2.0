<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Newsletter\Mappers\Subscriber as SubscriberMapper;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;

class Receiver extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'receiver',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'receiver', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuNewsletter',
            $items
        );
    }

    public function indexAction()
    {
        $subscriberMapper = new SubscriberMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('receiver'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $subscriberModel = new SubscriberModel();

            if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $email) {
                    $subscriberMapper->deleteEmail($email);
                }
            }

            foreach ($this->getRequest()->getPost('check_users') as $userEmail) {
                if ($userEmail != '') {
                    $subscriberModel->setEmail($userEmail);
                    $subscriberMapper->saveEmail($subscriberModel);
                }
            }
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('emails', $subscriberMapper->getMail());
        $this->getView()->set('userList', $subscriberMapper->getSendMailUser());
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $subscriberMapper = new SubscriberMapper();

            $subscriberMapper->deleteSubscriberBySelector($this->getRequest()->getParam('selector'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
