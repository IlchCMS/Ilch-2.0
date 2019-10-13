<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Mail extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuUserList'))
            ->add($user->getName())
            ->add($this->getTranslator()->trans('menuMail'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index'])
            ->add($user->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
            ->add($this->getTranslator()->trans('menuMail'), ['action' => 'index', 'user' => $this->getRequest()->getParam('user')]);

        if ($this->getRequest()->getPost('saveContact')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'subject' => 'required',
                'message' => 'required'
            ]);

            if ($validation->isValid()) {
                $sender = $userMapper->getUserById($this->getUser()->getId());
                $message = trim($this->getRequest()->getPost('message'));
                $date = new \Ilch\Date();

                $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                $subject = $this->getLayout()->escape($this->getRequest()->getPost('subject'));

                $layout = '';
                if (isset($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/usermail.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/usermail.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/usermail.php');
                }
                $messageReplace = [
                    '{senderMail}' => $this->getLayout()->escape($sender->getEmail()),
                    '{senderName}' => $this->getLayout()->escape($sender->getName()),
                    '{from}' => $this->getTranslator()->trans('mailFrom'),
                    '{writes}' => $this->getTranslator()->trans('writes'),
                    '{writeBackLink}' => $this->getTranslator()->trans('mailWriteBackLink'),
                    '{reply}' => $this->getTranslator()->trans('reply'),
                    '{subject}' => $subject,
                    '{content}' => $this->getLayout()->escape($message),
                    '{sitetitle}' => $siteTitle,
                    '{date}' => $date->format("l, d. F Y", true),
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($siteTitle)
                    ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                    ->setToName($this->getLayout()->escape($user->getName()))
                    ->setToEmail($this->getLayout()->escape($user->getEmail()))
                    ->setSubject($subject)
                    ->setMessage($message)
                    ->sent();

                $this->redirect()
                    ->withMessage('emailSuccess')
                    ->to(['controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')]);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index', 'user' => $this->getRequest()->getParam('user')]);
        }

        $this->getView()->set('user', $user);
    }
}
