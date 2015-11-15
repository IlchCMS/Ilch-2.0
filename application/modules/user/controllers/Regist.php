<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Service\Password as PasswordService;

class Regist extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getConfig()->get('regist_accept') == 1){
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuRegist'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('step1to3'), array('action' => 'index'));

            if ($this->getRequest()->getPost('saveRegist')) {
                if ($this->getRequest()->getPost('acceptRule') == 1) {
                    $this->redirect(array('action' => 'input'));
                } else {
                    $this->getView()->set('error', true);
                    $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
                    $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
                }
            } else {
                $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
                $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
            }
        } else {
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

        if ($this->getRequest()->getPost('saveRegist')) {
            $name = $this->getRequest()->getPost('name');
            $password = $this->getRequest()->getPost('password');
            $password2 = $this->getRequest()->getPost('password2');
            $email = trim($this->getRequest()->getPost('email'));
            $captcha = trim(strtolower($this->getRequest()->getPost('captcha')));

            $profilName = $registMapper->getUserByName($name);
            $profilEmail = $registMapper->getUserByEmail($email);

            if (empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
                $errors['captcha'] = 'invalidCaptcha';
            }

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
                    $model->setPassword((new PasswordService())->hash($password));
                    $model->setEmail($email);
                    $model->setDateCreated($currentDate);
                    $model->addGroup($userGroup);

                if ($this->getConfig()->get('regist_confirm') == 0){
                    $model->setDateConfirmed($currentDate);
                } else {
                    $confirmedCode = md5(uniqid(rand()));
                    $model->setConfirmed(0);
                    $model->setConfirmedCode($confirmedCode);
                }
                $registMapper->save($model);

                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

                if ($this->getConfig()->get('regist_confirm') == 1) {
                    $sitetitle = $this->getConfig()->get('page_title');
                    $confirmCode = '<a href="'.BASE_URL.'/index.php/user/regist/confirm/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirmMailButtonText').'</a>';
                    $date = new \Ilch\Date();
                    $layout = '';

                    if (isset($_SESSION['layout'])) {
                        $layout = $_SESSION['layout'];
                    }

                    if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/registconfirm.php')) {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/registconfirm.php');
                    } else {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/registconfirm.php');
                    }
                    $messageReplace = array(
                            '{content}' => $this->getConfig()->get('regist_confirm_mail'),
                            '{sitetitle}' => $sitetitle,
                            '{date}' => $date->format("l, d. F Y", true),
                            '{name}' => $name,
                            '{confirm}' => $confirmCode,
                            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    );
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setTo($email,$name)
                            ->setSubject($this->getTranslator()->trans('automaticEmail'))
                            ->setFrom($this->getTranslator()->trans('automaticEmail'), $sitetitle)
                            ->setMessage($message)
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
            
        } else {
            $userMapper = new UserMapper();
            $confirmed = $this->getRequest()->getParam('code');
            $user = $userMapper->getUserByConfirmedCode($confirmed);
            
            if (!empty($confirmed)) {
                if(!empty($user)) {
                    $currentDate = new \Ilch\Date();
                    $user->setDateConfirmed($currentDate);
                    $user->setConfirmed(1);
                    $user->setConfirmedCode('');
                    $userMapper->save($user);

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


