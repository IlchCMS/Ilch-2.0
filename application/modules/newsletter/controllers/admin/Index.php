<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuNewsletter', 
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'receiver',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
            )
        );
    }

    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'));

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $newsletterId) {
                    $newsletterMapper->delete($newsletterId);
                }
            }
        }

        $entries = $newsletterMapper->getEntries();

        $this->getView()->set('entries', $entries);
    }

    public function delAction()
    {
        $newsletterMapper = new NewsletterMapper();

        if ($this->getRequest()->isSecure()) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function showAction()
    {        
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('show'), array('action' => 'show'));

        if ($this->getRequest()->isPost('delete')) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(array('action' => 'index'));
        }

        $this->getView()->set('newsletter', $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id')));
    }

    public function treatAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));

        if ($this->getRequest()->isPost()) {
            $newsletterModel = new NewsletterModel();

            $subject = trim($this->getRequest()->getPost('subject'));
            $text = trim($this->getRequest()->getPost('text'));

            if (empty($subject)) {
                $this->addMessage('missingSubject', 'danger');
            } elseif (empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $date = new \Ilch\Date();
                $newsletterModel->setDateCreated($date);
                $newsletterModel->setUserId($this->getUser()->getId());
                $newsletterModel->setSubject($this->getRequest()->getPost('subject'));
                $newsletterModel->setText($this->getRequest()->getPost('text'));
                $newsletterMapper->save($newsletterModel);

                if ($_SESSION['layout'] == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/mail/newsletter.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/mail/newsletter.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/newsletter/layouts/mail/newsletter.php');
                }

                $emails = $newsletterMapper->getMail();
                foreach ($emails as $email) {
                    $messageReplace = array(
                            '{subject}' => $this->getRequest()->getPost('subject'),
                            '{content}' => $this->getRequest()->getPost('text'),
                            '{sitetitle}' => $this->getConfig()->get('page_title'),
                            '{date}' => $date->format("l, d. F Y", true),
                            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter'),
                            '{unreadable}' => $this->getTranslator()->trans('mailUnreadable', $newsletterMapper->getLastId(), $email->getEmail()),
                            '{unsubscribe}' => $this->getTranslator()->trans('mailUnsubscribe', $email->getEmail()),
                    );
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);
                    
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email->getEmail(), '')
                            ->setSubject($this->getRequest()->getPost('subject'))
                            ->setFrom($this->getConfig()->get('standardMail'), $this->getConfig()->get('page_title'))
                            ->setMessage($message)
                            ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                    $mail->send();
                }

                $this->addMessage('sendSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $emails = $newsletterMapper->getMail();
        $this->getView()->set('emails', $emails);
    }
}
