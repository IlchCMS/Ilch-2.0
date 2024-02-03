<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Date;
use Ilch\Mail;
use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Mappers\Subscriber as SubscriberMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'receiver',
                'active' => false,
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

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuNewsletter',
            $items
        );
    }

    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();
        $subscriberMapper = new SubscriberMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        // Another occasion to delete possible old unconfirmed entries of subscribers.
        $subscriberMapper->deleteOldUnconfirmedDoubleOptIn();

        if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_entries') as $newsletterId) {
                $newsletterMapper->delete($newsletterId);
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

        if ($this->getRequest()->isPost()) {
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
        $subscriberMapper = new SubscriberMapper();
        $date = new Date();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);

        if ($this->getRequest()->isPost()) {
            $newsletterModel = new NewsletterModel();

            $post = [
                'subject' => trim($this->getRequest()->getPost('subject')),
                'text' => trim($this->getRequest()->getPost('text'))
            ];

            $validation = Validation::create($post, [
                'subject'   => 'required',
                'text'      => 'required'
            ]);

            if ($validation->isValid()) {
                $newsletterModel->setDateCreated($date);
                $newsletterModel->setUserId($this->getUser()->getId());
                $newsletterModel->setSubject($post['subject']);
                $newsletterModel->setText($post['text']);
                $newsletterMapper->save($newsletterModel);

                $layout = '';
                if (!empty($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/mail/newsletter.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/newsletter/layouts/mail/newsletter.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/newsletter/layouts/mail/newsletter.php');
                }

                $emails = $subscriberMapper->getMail();
                foreach ($emails as $email) {
                    if (!$email->getDoubleOptInConfirmed()) {
                        continue;
                    }

                    $messageReplace = [
                        '{subject}' => $this->getLayout()->escape($post['subject']),
                        '{content}' => $this->getLayout()->purify($post['text']),
                        '{sitetitle}' => $this->getLayout()->escape($this->getConfig()->get('page_title')),
                        '{date}' => $date->format('l, d. F Y', true),
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter'),
                        '{unreadable}' => $this->getTranslator()->trans('mailUnreadable', $newsletterMapper->getLastId(), $email->getEmail()),
                        '{unsubscribe}' => $this->getTranslator()->trans('mailUnsubscribe', $email->getSelector(), $email->getConfirmCode()),
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new Mail();
                    $mail->setFromName($this->getConfig()->get('page_title'))
                        ->setFromEmail($this->getConfig()->get('standardMail'))
                        ->setToName($email->getEmail())
                        ->setToEmail($email->getEmail())
                        ->setSubject($this->getLayout()->escape($post['subject']))
                        ->setMessage($message)
                        ->send();
                }

                $this->redirect()
                    ->withMessage('sendSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($post)
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'treat']);
            }
        }

        $this->getView()->set('emails', $subscriberMapper->getMail());
    }
}
