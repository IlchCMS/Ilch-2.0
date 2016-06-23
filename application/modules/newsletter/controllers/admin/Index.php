<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
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
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'receiver', 'action' => 'index'])
            ],
            [
                'name' => 'add',
                'active' => false,
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'settings') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[2]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuNewsletter',
            $items
        );
    }

    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $newsletterId) {
                    $newsletterMapper->delete($newsletterId);
                }
            }
        }

        $entries = $newsletterMapper->getEntries();

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('entries', $entries);
    }

    public function delAction()
    {
        $newsletterMapper = new NewsletterMapper();

        if ($this->getRequest()->isSecure()) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function showAction()
    {        
        $newsletterMapper = new NewsletterMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('show'), ['action' => 'show']);

        if ($this->getRequest()->isPost('delete')) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('newsletter', $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id')));
    }

    public function treatAction()
    {
        $newsletterMapper = new NewsletterMapper();
        $date = new \Ilch\Date();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);

        if ($this->getRequest()->isPost()) {
            $newsletterModel = new NewsletterModel();

            $subject = trim($this->getRequest()->getPost('subject'));
            $text = trim($this->getRequest()->getPost('text'));

            if (empty($subject)) {
                $this->addMessage('missingSubject', 'danger');
            } elseif (empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $newsletterModel->setDateCreated($date);
                $newsletterModel->setUserId($this->getUser()->getId());
                $newsletterModel->setSubject($subject);
                $newsletterModel->setText($text);
                $newsletterMapper->save($newsletterModel);

                if ($_SESSION['layout'] == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/mail/newsletter.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/newsletter/layouts/mail/newsletter.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/newsletter/layouts/mail/newsletter.php');
                }

                $emails = $newsletterMapper->getMail();
                foreach ($emails as $email) {
                    $messageReplace = [
                        '{subject}' => $subject,
                        '{content}' => $text,
                        '{sitetitle}' => $this->getConfig()->get('page_title'),
                        '{date}' => $date->format("l, d. F Y", true),
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter'),
                        '{unreadable}' => $this->getTranslator()->trans('mailUnreadable', $newsletterMapper->getLastId(), $email->getEmail()),
                        '{unsubscribe}' => $this->getTranslator()->trans('mailUnsubscribe', $email->getEmail()),
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setTo($email->getEmail(), '')
                            ->setSubject($subject)
                            ->setFrom($this->getConfig()->get('standardMail'), $this->getConfig()->get('page_title'))
                            ->setMessage($message)
                            ->addGeneralHeader('Content-Type', 'text/html; charset="utf-8"');
                    $mail->setAdditionalParameters('-f '.$this->getConfig()->get('standardMail'));
                    $mail->send();
                }

                $this->addMessage('sendSuccess');

                $this->redirect(['action' => 'index']);
            }
        }

        $this->getView()->set('emails', $newsletterMapper->getMail());
    }
}
