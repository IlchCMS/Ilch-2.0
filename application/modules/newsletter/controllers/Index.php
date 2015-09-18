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
}
