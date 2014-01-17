<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers;

use User\Mappers\User as UserMapper;

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
}
