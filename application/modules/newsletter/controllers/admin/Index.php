<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
                (
                'menuNewsletter', array
            (
            array
                (
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
            ),
                )
        );

        $this->getLayout()->addMenuAction
                (
                array
                    (
                    'name' => 'menuActionNewNewsletter',
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'));

        $newsletterMapper = new NewsletterMapper();

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
        if ($this->getRequest()->isSecure()) {
            $newsletterMapper = new NewsletterMapper();
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function showAction()
    {
        if ($this->getRequest()->isPost('delete')) {
            $newsletterMapper = new NewsletterMapper();
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(array('action' => 'index'));
        }
        $newsletterMapper = new NewsletterMapper();
        $this->getView()->set('newsletter', $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id')));
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuNewsletterTreat'), array('action' => 'treat'));

        $newsletterMapper = new NewsletterMapper();
        $emails = $newsletterMapper->getMail();

        if ($this->getRequest()->isPost()) {
            $model = new NewsletterModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $subject = trim($this->getRequest()->getPost('subject'));
            $text = trim($this->getRequest()->getPost('text'));

            if (empty($subject)) {
                $this->addMessage('missingSubject', 'danger');
            } elseif (empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $date = new \Ilch\Date();
                $model->setDateCreated($date);
                $model->setUserId($this->getUser()->getId());
                $model->setSubject($this->getRequest()->getPost('subject'));
                $model->setText($this->getRequest()->getPost('text'));
                $newsletterMapper->save($model);

                foreach ($emails as $email) {
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email->getEmail(), '')
                            ->setSubject($this->getRequest()->getPost('subject'))
                            ->setFrom($this->getConfig()->get('standardMail'), $this->getConfig()->get('page_title'))
                            ->setMessage($this->getRequest()->getPost('text'))
                            ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                    $mail->send();
                }

                $this->addMessage('sendSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('emails', $emails);
    }
}
