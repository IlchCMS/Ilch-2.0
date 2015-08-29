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
            } elseif (empty($password)) {
                $errors['loginContent_password'] = 'fieldEmpty';
            } else {
                $mapper = new UserMapper();
                $user = $mapper->getUserByEmail($emailName);

                if ($user == null) {
                    $user = $mapper->getUserByName($emailName);
                }

                if ($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('loginContent_password'), $user->getPassword())) {
                    $_SESSION['messages'][] = array('text' => 'Sie haben einen fehlerhaften Benutzername, E-Mail oder Passwort angegeben. Bitte prüfen Sie ihre Angaben und versuche Sie es erneut.', 'type' => 'warning');
                } elseif ($user->getConfirmed() == 0) {               
                    $_SESSION['messages'][] = array('text' => 'Benutzer nicht freigeschaltet! Bitte bestätigen Sie ihren Account in der verschickten E-Mail oder fordern Sie eine neue E-Mail mit einen Freischaltlink an.', 'type' => 'warning');
                    
                    $this->redirect(array('module' => 'user', 'controller' => 'login', 'action' => 'index'));
                } else {
                    $_SESSION['user_id'] = $user->getId();
                    
                    $_SESSION['messages'][] = array('text' => 'Sie haben sich erfolgreich eingeloggt.', 'type' => 'success');
                    $this->redirect();
                }
            }

            $this->getView()->set('errors', $errors);
        }

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
    }

    public function newpasswordAction()
    {        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUser'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('newPassword'), array('action' => 'newpassword'));

        if ($this->getRequest()->getPost('saveNewPassword')) {
            $confirmedCode = $this->getRequest()->getParam('code');

            if (empty($confirmedCode)) {
                $this->addMessage('missingConfirmedCode', 'danger');
            } else {
                $userMapper = new UserMapper();
                $user = $userMapper->getUserByConfirmedCode($confirmedCode);

                if(!empty($user)) {
                    $password = trim($this->getRequest()->getPost('password'));
                    $password2 = trim($this->getRequest()->getPost('password2'));

                    if (empty($password)) {
                        $this->addMessage('passwordEmpty', $type = 'danger');
                        $this->redirect(array('action' => 'newpassword', 'code' => $confirmedCode));
                    } elseif (empty($password2)) {
                        $this->addMessage('passwordRetypeEmpty', $type = 'danger');
                        $this->redirect(array('action' => 'newpassword', 'code' => $confirmedCode));
                    } elseif (strlen($password) < 6 OR strlen($password) > 30) {
                        $this->addMessage('passwordLength', $type = 'danger');
                        $this->redirect(array('action' => 'newpassword', 'code' => $confirmedCode));
                    } elseif ($password != $password2) {
                        $this->addMessage('passwordNotEqual', $type = 'danger');
                        $this->redirect(array('action' => 'newpassword', 'code' => $confirmedCode));
                    }

                    if (!empty($password) AND !empty($password2) AND $password == $password2) {
                        $password = crypt($password);
                        $user->setConfirmed(1);
                        $user->setConfirmedCode('');
                        $user->setPassword($password);
                        $userMapper->save($user);

                        $this->addMessage('newPasswordSuccess');
                        $this->redirect(array('action' => 'index'));
                    }
                } else {
                    $this->addMessage('newPasswordFailed', 'danger');                    
                }
            }
        }
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
                    $user->setConfirmed(0);
                    $user->setConfirmedCode($confirmedCode);
                    $userMapper->save($user);

                    $name = $user->getName();
                    $email = $user->getEmail();
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email,$name)
                            ->setSubject('Automatische E-Mail')
                            ->setFrom('Automatische E-Mail', $this->getConfig()->get('page_title'))
                            ->setMessage('Hallo '.$name.',<br><br>um ihr neues Passwort zu erstellen klicke Sie bitte auf folgenden Link. <a href="'.BASE_URL.'/index.php/user/login/newpassword/code/'.$confirmedCode.'">BITTE HIER KLICKEN</a>\n\nMit freundlichen Grüßen\nAdministrator.')
                            ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                    $mail->send();

                    $this->addMessage('newPasswordEMailSuccess');
                } else {
                    $this->addMessage('newPasswordFailed', 'danger');                    
                }
            }
        }
    }
}
