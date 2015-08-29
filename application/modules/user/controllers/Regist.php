<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

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

            $profilName = $registMapper->getUserByName($name);
            $profilEmail = $registMapper->getUserByEmail($email);

            if (!empty($profilName)) {
                $errors['name'] = 'nameExist';
            }

            if (!empty($profilEmail)) {
                $errors['email'] = 'emailExist';
            }

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
                    $groupMapper = new \Modules\User\Mappers\Group();
                    $userGroup = $groupMapper->getGroupById(2);
                    $currentDate = new \Ilch\Date();
                    $model = new \Modules\User\Models\User();
                    $model->setName($name);
                    $model->setPassword(crypt($password));
                    $model->setEmail($email);
                    $model->setDateCreated($currentDate);
                    $model->addGroup($userGroup);

                if ($this->getConfig()->get('regist_confirm') == 0){
                    $model->setDateConfirmed($currentDate);
                }else{
                    $confirmedCode = md5(uniqid(rand()));
                    $model->setConfirmed(0);
                    $model->setConfirmedCode($confirmedCode);
                }

                $registMapper->save($model);

                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

                if($this->getConfig()->get('regist_confirm') == '0'){
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email,$name)
                            ->setSubject('Automatische E-Mail')
                            ->setFrom('Automatische E-Mail', $this->getConfig()->get('page_title'))
                            ->setMessage('Hallo '.$name.',\n\nWillkommen auf '.$this->getConfig()->get('page_title').' Sie können sich nun mit ihren Angegebenen Datein einloggen.\n\nMit freundlichen Grüßen\nAdministrator.')
                            ->addGeneralHeader('Content-type', 'text/plain; charset="utf-8"');
                    $mail->send();
                } else {
                    $mail = new \Ilch\Mail();
                    $mail->setTo($email,$name)
                            ->setSubject('Automatische E-Mail')
                            ->setFrom('Automatische E-Mail', $this->getConfig()->get('page_title'))
                            ->setMessage('Hallo '.$name.',\n\num die Registrierung erfolgreich abzuschließen klicke Sie Bitte auf folgenden Link. <a href="'.BASE_URL.'/index.php/user/regist/confirm/code/'.$confirmedCode.'">BITTE HIER KLICKEN</a>\n\nMit freundlichen Grüßen\nAdministrator.')
                            ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                    $mail->send();
                }

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
        
        if ($this->getRequest()->getPost('saveConfirm')) {
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
                    $user->setConfirmed(1);
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


