<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers;

use User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Regist extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getConfig()->get('regist_accept') == 1){
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('step1to3'), array('action' => 'index'));

            if ($this->getRequest()->isPost()) {
                if ($this->getRequest()->getPost('acceptRule')) {
                    $this->redirect(array('action' => 'input'));
                } else {
                    $this->getView()->set('error', true);
                    $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
                    $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
                }
            }else{
                $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
                $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
            }
        }else{
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'));
        
            $this->getView();   
        }
    }
    
    public function inputAction()
    {    
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('step2to3'), array('action' => 'input'));
        
        $registMapper = new UserMapper();
        $errors = array();

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $password = $this->getRequest()->getPost('password');
            $password2 = $this->getRequest()->getPost('password2');
            $email = trim($this->getRequest()->getPost('email'));

            if (empty($name)) {
                $errors['name'] = 'fieldEmpty';
            }

            if (empty($password)) {
                $errors['password'] = 'fieldEmpty';
            }

            if (empty($password2)) {
                $errors['password2'] = 'fieldEmpty';
            }

            if ($password !== $password2) {
                $errors['password'] = 'fieldDiffersPassword';
                $errors['password2'] = 'fieldDiffersPassword';
            }     

            if (empty($email)) {
                $errors['email'] = 'fieldEmpty';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'fieldEmail';
            }
            
            if (empty($errors)) {
                if ($this->getConfig()->get('regist_confirm') == 0){  
                    $currentDate = new \Ilch\Date(); 
                    $model = new \User\Models\User();
                    $model->setName($name);
                    $model->setPassword(crypt($password));
                    $model->setEmail($email);
                    $model->setDateCreated($currentDate);
                    $model->setDateConfirmed($currentDate);
                    $registMapper->save($model);
                }else{        
                    $currentDate = new \Ilch\Date(); 
                    $confirmedCode = md5(uniqid(rand()));
                    $model = new \User\Models\User();
                    $model->setName($name);
                    $model->setPassword(crypt($password));
                    $model->setEmail($email);
                    $model->setDateCreated($currentDate);
                    $model->setConfirmed(1);
                    $model->setConfirmedCode($confirmedCode);
                    $registMapper->save($model);                   
                }
                
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

                $this->redirect(array('action' => 'finish'));
            }
            
            $this->getView()->set('errors', $errors); 
        }
        
        $this->getView(); 
    }

    public function finishAction()
    {        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('step3to3'), array('action' => 'finish'));
        
        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'));    
    }

    public function confirmAction()
    {        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuConfirm'), array('action' => 'confirm'));
        
        $errors = array();
        
        if ($this->getRequest()->isPost()) {
            $confirmedCode = $this->getRequest()->getPost('confirmedCode');

            if (empty($confirmedCode)) {
                $errors['confirmedCode'] = 'fieldEmpty';
            }
            
            if (empty($errors)) {                
                $this->redirect(array('controller' => 'regist', 'action' => 'confirm', 'code' => $confirmedCode));
            }
            
            $this->getView()->set('errors', $errors);
            
        }else{
            $userMapper = new UserMapper();
            $confirmed = $this->getRequest()->getParam('code');
            $user = $userMapper->getUserByConfirmedCode($confirmed);
            
            if (!empty($confirmed)) {
                if(!empty($user)) {
                    $user->setConfirmedCode($confirmed);
                    $user->setConfirmed(0);
                    $user->setConfirmedCode('');
                    $userId = $userMapper->save($user);

                    $confirmed = '1';
                    $this->getView()->set('confirmed', $confirmed);
                } else {   
                    $confirmed = null;
                    $this->getView()->set('confirmed', $confirmed);
                    
                    $_SESSION['messages'][] = array('text' => 'Aktivierungscode Falsch', 'type' => 'warning');
                }                
            } else {
                $this->getView();
            }
        }
    }
}


