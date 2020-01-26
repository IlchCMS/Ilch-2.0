<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Receiver extends \Ilch\Controller\Admin
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
                'name' => 'receiver',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'receiver', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuNewsletter',
            $items
        );
    }

    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('receiver'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $newsletterModel = new NewsletterModel();

            if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $email) {
                    $newsletterMapper->deleteEmail($email);
                }
            }

            foreach ($this->getRequest()->getPost('check_users') as $userEmail) {
                if ($userEmail != '') {
                    $newsletterModel->setEmail($userEmail);
                    $newsletterMapper->saveEmail($newsletterModel);
                }
            }
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('emails', $newsletterMapper->getMail());
        $this->getView()->set('userList', $newsletterMapper->getSendMailUser());
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $newsletterMapper = new NewsletterMapper();

            $newsletterMapper->deleteSubscriberBySelector($this->getRequest()->getParam('selector'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
