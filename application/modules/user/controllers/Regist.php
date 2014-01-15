<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers;

use User\Mappers\User as UserMapper;
use User\Mappers\Confirm as ConfirmMapper;

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
                    $model->setDateLastActivity($currentDate);
                    $registMapper->save($model);
                }else{        
                    $model = new \User\Models\User();
                    $model->setName($name);
                    $model->setPassword(crypt($password));
                    $model->setEmail($email);
                    $registMapper->saveCheck($model);                    
                }
                
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

                $this->redirect(array('action' => 'finish'));
            }
            
            $this->getView()->set('errors', $errors);
        }
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
        
        $confirmMapper = new ConfirmMapper();
        $errors = array();
        
        if ($this->getRequest()->isPost()) {
            $check = $this->getRequest()->getPost('check');

            if (empty($check)) {
                $errors['check'] = 'fieldEmpty';
            }
            
            if (empty($errors)) {                
                $this->redirect(array('controller' => 'regist', 'action' => 'confirm', 'check' => $check));
            }
            
            $this->getView()->set('errors', $errors);
            
        }else{
            if ($this->getRequest()->getParam('check')) {
                $check = $this->getRequest()->getParam('check');
                $checks = $confirmMapper->getCheck(array('check' => $check));
                
                if (!empty($checks)) {
                    $registMapper = new UserMapper();
                    
                    $checkCode = $this->getRequest()->getParam('check');
                    
                    foreach($checks as $checks) {          
                        $name = $checks->getName();
                        $password = $checks->getPassword();
                        $email = $checks->getEmail();
                        $date_created = $checks->getDateCreated();
                    }

                    $currentDate = new \Ilch\Date();
                    $model = new \User\Models\User();
                    $model->setName($name);
                    $model->setPassword($password);
                    $model->setEmail($email);
                    $model->setDateCreated($date_created);
                    $model->setDateConfirmed($currentDate);
                    $model->setDateLastActivity($currentDate);
                    $registMapper->save($model);
                    
                    $deleteUser = new ConfirmMapper();
                    $deleteUser->delete($this->getRequest()->getParam('check'));
                    
                    $this->getView()->set('checks', $checks); 
                }else{
                    $this->getView();                    
                }
            }else{
                $this->getView();
            }
        }
    }
}


