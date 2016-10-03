<?php
/**
 * @copyright Ilch 2.0
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
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveNewsletter')) {
            $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
            if ($countEmails == 0) {
                $newsletterModel =NewsletterModel();
                $newsletterModel->setEmail($this->getRequest()->getPost('email'));
                $newsletterMapper->saveEmail($newsletterModel);

                $this->addMessage('subscribeSuccess');
            } else {
                $newsletterMapper->deleteEmail($this->getRequest()->getPost('email'));

                $this->addMessage('unsubscribeSuccess');
            }
        }

        $this->getView();
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
        if ($newsletter != '') {
            $this->getView()->set('newsletter', $newsletter);            
        } else {
            $this->redirect(['action' => 'index']);            
        }
    }

    public function unsubscribeAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $countEmail = $newsletterMapper->countEmails($this->getRequest()->getParam('email'));
        if ($countEmail == 1) {
            $newsletterMapper->deleteEmail($this->getRequest()->getParam('email'));

            $this->addMessage('unsubscribeSuccess');
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

        $post = [
            'acceptNewsletter' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'acceptNewsletter' => $this->getRequest()->getPost('acceptNewsletter')
            ];

            $validation = Validation::create($post, [
                'acceptNewsletter' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $newsletterModel = new NewsletterModel();
                $newsletterModel->setId($this->getUser()->getId());
                $newsletterModel->setNewsletter($post['acceptNewsletter']);
                $newsletterMapper->saveUserEmail($newsletterModel);

                $this->redirect(['action' => 'settings']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('countMail', $newsletterMapper->countEmails($this->getUser()->getEmail()));
        $this->getView()->set('usermenu', $UserMenuMapper->getUserMenu());
        $this->getView()->set('profil', $userMapper->getUserById($this->getUser()->getId()));
        $this->getView()->set('galleryAllowed', $this->getConfig()->get('usergallery_allowed'));
    }
}
