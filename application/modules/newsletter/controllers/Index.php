<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'));

        if ($this->getRequest()->getPost('saveNewsletter')) {
            $countEmails = $newsletterMapper->countEmails($this->getRequest()->getPost('email'));
            if ($countEmails == 0) {
                $newsletterModel = new \Modules\Newsletter\Models\Newsletter();
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
        if (file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/show.php')) {
            $this->getLayout()->setFile('layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/show');
        } else {
            $this->getLayout()->setFile('modules/newsletter/layouts/show');
        }

        $newsletterMapper = new NewsletterMapper();

        $newsletter = $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id'));
        if ($newsletter != '') {
            $this->getView()->set('newsletter', $newsletter);            
        } else {
            $this->redirect(array('action' => 'index'));            
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

        $this->redirect(array('action' => 'index'));
    }
}
