<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Controllers;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Usermenu as UserMenuMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $this->redirect(['action' => 'settings']);
        }
        
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveNewsletter')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
                if ($countEmails == 0) {
                    $newsletterModel = new NewsletterModel();
                    $newsletterModel->setSelector(bin2hex(random_bytes(9)));
                    $newsletterModel->setConfirmCode(bin2hex(random_bytes(32)));
                    $newsletterModel->setEmail($this->getRequest()->getPost('email'));
                    $newsletterMapper->saveEmail($newsletterModel);
                }

                $this->addMessage('subscribeSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
            $this->redirect(['action' => 'index']);
        }
    }

    public function showAction()
    {
        $newsletterMapper = new NewsletterMapper();

        if (file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/show.php')) {
            $this->getLayout()->setFile('layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/show');
        } else {
            $this->getLayout()->setFile('modules/newsletter/layouts/show');
        }

        $newsletter = $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id'));
        if ($newsletter !== null) {
            $this->getView()->set('newsletter', $newsletter);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function unsubscribeAction()
    {
        $selector = $this->getRequest()->getParam('selector');
        $confirmCode = $this->getRequest()->getParam('code');

        if (empty($confirmCode) || empty($selector)) {
            $this->addMessage('incompleteUnsubscribeUrl', 'danger');
        } else {
            $newsletterMapper = new NewsletterMapper();

            $subscriber = $newsletterMapper->getSubscriberBySelector($selector);
            if (!empty($subscriber) && hash_equals($subscriber->getConfirmCode(), $confirmCode)) {
                $countEmail = $newsletterMapper->countEmails($subscriber->getEmail());
                if ($countEmail == 1) {
                    $newsletterMapper->deleteEmail($subscriber->getEmail());

                    $this->addMessage('unsubscribeSuccess');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function settingsAction()
    {
        $newsletterMapper = new NewsletterMapper();
        $userMapper = new UserMapper();
        $UserMenuMapper = new UserMenuMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), ['module' => 'user', 'controller' => 'panel', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['module' => 'user', 'controller' => 'panel', 'action' => 'settings'])
                ->add($this->getTranslator()->trans('menuNewsletter'), ['controller' => 'index', 'action' => 'settings']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'acceptNewsletter' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $newsletterModel = new NewsletterModel();
                $newsletterModel->setSelector(bin2hex(random_bytes(9)));
                $newsletterModel->setConfirmCode(bin2hex(random_bytes(32)));
                $newsletterModel->setId($this->getUser()->getId());
                $newsletterModel->setNewsletter($this->getRequest()->getPost('acceptNewsletter'));
                $newsletterMapper->saveUserEmail($newsletterModel);

                $this->redirect(['action' => 'settings']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('countMail', $newsletterMapper->countEmails($this->getUser()->getEmail()));
        $this->getView()->set('usermenu', $UserMenuMapper->getUserMenu());
        $this->getView()->set('profil', $userMapper->getUserById($this->getUser()->getId()));
        $this->getView()->set('galleryAllowed', $this->getConfig()->get('usergallery_allowed'));
    }
}
