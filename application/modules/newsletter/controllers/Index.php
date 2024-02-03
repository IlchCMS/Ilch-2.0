<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Controllers;

use Ilch\Controller\Frontend;
use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Mappers\Subscriber as SubscriberMapper;
use Modules\Newsletter\Models\Subscriber as SubscriberModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Usermenu as UserMenuMapper;
use Ilch\Validation;

class Index extends Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $this->redirect(['action' => 'settings']);
        }

        $subscriberMapper = new SubscriberMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveNewsletter')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'email' => 'required|email'
            ]);

            if ($validation->isValid()) {
                $countEmails = $subscriberMapper->countEmails($this->getRequest()->getPost('email'));
                if ($countEmails == 0) {
                    $subscriberModel = new SubscriberModel();
                    $subscriberModel->setSelector(bin2hex(random_bytes(9)));
                    $subscriberModel->setConfirmCode(bin2hex(random_bytes(32)));
                    $subscriberModel->setEmail($this->getRequest()->getPost('email'));
                    $subscriberMapper->saveEmail($subscriberModel);
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

        if (file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/show.php')) {
            $this->getLayout()->setFile('layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/show');
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

    public function doubleoptinAction()
    {
        $selector = $this->getRequest()->getParam('selector');
        $confirmCode = $this->getRequest()->getParam('code');

        if (empty($confirmCode) || empty($selector)) {
            $this->addMessage('incompleteDoubleOptInUrl', 'danger');
        } else {
            $subscriberMapper = new SubscriberMapper();

            $subscriber = $subscriberMapper->getSubscriberBySelector($selector);
            if (!empty($subscriber) && hash_equals($subscriber->getConfirmCode(), $confirmCode)) {
                $countEmail = $subscriberMapper->countEmails($subscriber->getEmail());
                if ($countEmail == 1) {
                    // Create new selector and confirmedCode for a possible future unsubscribe action.
                    $selector = bin2hex(random_bytes(9));
                    $confirmedCode = bin2hex(random_bytes(32));

                    $subscriberModel = new SubscriberModel();
                    $subscriberModel->setId($subscriber->getId());
                    $subscriberModel->setSelector($selector);
                    $subscriberModel->setConfirmCode($confirmedCode);
                    $subscriberModel->setEmail($subscriber->getEmail());
                    $subscriberModel->setNewsletter(1);
                    $subscriberModel->setDoubleOptInDate($subscriber->getDoubleOptInDate());
                    $subscriberModel->setDoubleOptInConfirmed(true);
                    $subscriberMapper->saveEmail($subscriberModel);

                    $this->addMessage('doubleOptInSuccess');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function unsubscribeAction()
    {
        $selector = $this->getRequest()->getParam('selector');
        $confirmCode = $this->getRequest()->getParam('code');

        if (empty($confirmCode) || empty($selector)) {
            $this->addMessage('incompleteUnsubscribeUrl', 'danger');
        } else {
            $subscriberMapper = new SubscriberMapper();

            $subscriber = $subscriberMapper->getSubscriberBySelector($selector);
            if (!empty($subscriber) && hash_equals($subscriber->getConfirmCode(), $confirmCode)) {
                $countEmail = $subscriberMapper->countEmails($subscriber->getEmail());
                if ($countEmail == 1) {
                    $subscriberMapper->deleteEmail($subscriber->getEmail());

                    $this->addMessage('unsubscribeSuccess');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function settingsAction()
    {
        $subscriberMapper = new SubscriberMapper();
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
                // No double-opt-in (if enabled) needed as the user needs to be logged in to subscribe from here.
                $subscriberModel = new SubscriberModel();
                $subscriberModel->setSelector(bin2hex(random_bytes(9)));
                $subscriberModel->setConfirmCode(bin2hex(random_bytes(32)));
                $subscriberModel->setId($this->getUser()->getId());
                $subscriberModel->setNewsletter($this->getRequest()->getPost('acceptNewsletter'));
                $subscriberMapper->saveUserEmail($subscriberModel);

                $this->redirect(['action' => 'settings']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('countMail', $subscriberMapper->countEmails($this->getUser()->getEmail()));
        $this->getView()->set('usermenu', $UserMenuMapper->getUserMenu());
        $this->getView()->set('profil', $userMapper->getUserById($this->getUser()->getId()));
        $this->getView()->set('galleryAllowed', $this->getConfig()->get('usergallery_allowed'));
    }
}
