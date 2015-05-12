<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Login extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLogin'), array('action' => 'index'));

        $errors = array();

        if ($this->getRequest()->isPost()) {
            if (\Ilch\Registry::get('user')) {
                $errors['alreadyLoggedIn'] = 'alreadyLoggedIn';
            }

            $emailName = $this->getRequest()->getPost('loginContent_emailname');
            $password = $this->getRequest()->getPost('loginContent_password');

            if (empty($emailName)) {
                $errors['loginContent_emailname'] = 'fieldEmpty';
            }elseif (empty($password)) {
                $errors['loginContent_password'] = 'fieldEmpty';
            }else {
                $mapper = new UserMapper();
                $user = $mapper->getUserByEmail($emailName);

                if ($user == null) {
                    $user = $mapper->getUserByName($emailName);
                }

                if ($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('loginContent_password'), $user->getPassword())) {
                    $_SESSION['messages'][] = array('text' => 'Sie haben einen fehlerhaften Benutzername, E-Mail oder Passwort angegeben. Bitte prüfen Sie ihre Angaben und versuche Sie es erneut.', 'type' => 'warning');
                } else {
                    $_SESSION['user_id'] = $user->getId();

                    $this->redirect(array('module' => 'user', 'controller' => 'login', 'action' => 'index'));  
                }
            }

            $this->getView()->set('errors', $errors);
        }

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
    }

    public function forgotpasswordAction()
    {        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuLogin'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuForgotPassword'), array('action' => 'forgotpassword'));

        if ($this->getRequest()->getPost('saveNewPassword')) {
            $name = trim($this->getRequest()->getPost('name'));

            if (empty($name)) {
                $this->addMessage('missingNameEmail', 'danger');
            } else {
                $userMapper = new UserMapper();
                $user = $userMapper->getUserByEmail($name);

                if ($user == null) {
                    $user = $userMapper->getUserByName($name);
                }

                if(!empty($user)) {
                    $confirmedCode = md5(uniqid(rand()));
                    $password = substr(md5(rand()),0,10);
                    $user->setConfirmed(1);
                    $user->setConfirmedCode($confirmedCode);
                    $user->setPassword(crypt($password));
                    $userMapper->save($user);

                    $name = $user->getName();
                    $email = $user->getEmail();
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email,$name)
                            ->setSubject('Automatische E-Mail')
                            ->setFrom('Automatische E-Mail', $this->getConfig()->get('page_title'))
                            ->setMessage('Hallo '.$name.',<br><br>dein neues Passwort: '.$password.'<br><br>um das neue Passwort zu bestätigen einfach auf den folgenden Link klicken. <a href="'.BASE_URL.'/index.php/user/regist/confirm/code/'.$confirmedCode.'">BITTE HIER KLICKEN</a>')
                            ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                    $mail->send();

                    $this->addMessage('newPasswordSuccess');
                } else {
                    $this->addMessage('newPasswordFailed', 'danger');                    
                }
            }
        }
    }
}
