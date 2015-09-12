<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Mail extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getRequest()->getParam('user'));
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), array('controller' => 'index'))
                ->add($profil->getName(), array('controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($this->getTranslator()->trans('menuMail'), array('action' => 'index', 'user' => $this->getRequest()->getParam('user')));

        if ($this->getRequest()->isPost()) {
            $sender = $profilMapper->getUserById($this->getUser()->getId());
            $name = $sender->getName();
            $email = $sender->getEmail();         
            $subject = trim($this->getRequest()->getPost('subject'));
            $message = trim($this->getRequest()->getPost('message'));

            if (empty($subject)) {
                $this->addMessage('subjectEmpty');
                $this->redirect(array('action' => 'index', 'user' => $this->getRequest()->getParam('user')));
            } elseif (empty($message)) {
                $this->addMessage('messageEmpty');
                $this->redirect(array('action' => 'index', 'user' => $this->getRequest()->getParam('user')));
            } else {
                $mail = new \Ilch\Mail();
                $mail->setTo($profil->getEmail(), $profil->getName())
                        ->setSubject($subject)
                        ->setFrom($email, $name)
                        ->setMessage($message)
                        ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                $mail->send();

                $this->addMessage('emailSuccess');
                $this->redirect(array('controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')));
            }
        }

        $this->getView()->set('profil', $profil);
    }
}
