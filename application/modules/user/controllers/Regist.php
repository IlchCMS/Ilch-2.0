<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Service\Password as PasswordService;
use Ilch\Validation;

class Regist extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getConfig()->get('regist_accept') == 1) {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('step1to3'), ['action' => 'index']);

            if ($this->getRequest()->getPost('saveRegist')) {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'acceptRule' => 'required'
                ]);

                if ($validation->isValid()) {
                    $this->redirect()
                        ->to(['action' => 'input']);
                }

                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            } else {
                $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
                $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
            }
        } else {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index']);
        }
    }

    public function inputAction()
    {
        $registMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('step2to3'), ['action' => 'input']);

        if ($this->getRequest()->getPost('saveRegist') AND $this->getRequest()->getPost('bot') === '') {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required|unique:users,name',
                'password' => 'required|min:6:string|max:30:string',
                'password2' => 'required|same:password|min:6:string|max:30:string',
                'email' => 'required|email|unique:users,email',
                'captcha' => 'captcha'
            ]);

            if ($validation->isValid()) {
                $groupMapper = new GroupMapper();
                $userGroup = $groupMapper->getGroupById(2);
                $currentDate = new \Ilch\Date();

                $model = new UserModel();
                if ($this->getConfig()->get('regist_confirm') == 0) {
                    $model->setDateConfirmed($currentDate->format("Y-m-d H:i:s", true));
                } else {
                    $selector = bin2hex(openssl_random_pseudo_bytes(9));
                    // 33 bytes instead of 32 bytes just that the confirmedCode to confirm a registration
                    // is different from the one to change a password and therefore can only be used for this purpose.
                    $confirmedCode = bin2hex(openssl_random_pseudo_bytes(33));
                    $model->setSelector($selector);
                    $model->setConfirmedCode($confirmedCode);
                    $model->setConfirmed(0);
                }
                $model->setName($this->getRequest()->getPost('name'))
                    ->setPassword((new PasswordService())->hash($this->getRequest()->getPost('password')))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setDateCreated($currentDate->format("Y-m-d H:i:s", true))
                    ->addGroup($userGroup);
                $registMapper->save($model);

                $_SESSION["name"] = $this->getRequest()->getPost('name');
                $_SESSION["email"] = $this->getRequest()->getPost('email');

                if ($this->getConfig()->get('regist_confirm') == 1) {
                    $sitetitle = $this->getConfig()->get('page_title');
                    $confirmCode = '<a href="'.BASE_URL.'/index.php/user/regist/confirm/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirmMailButtonText').'</a>';
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
                    $messageReplace = [
                        '{content}' => $this->getConfig()->get('regist_confirm_mail'),
                        '{sitetitle}' => $sitetitle,
                        '{date}' => $date->format("l, d. F Y", true),
                        '{name}' => $this->getRequest()->getPost('name'),
                        '{confirm}' => $confirmCode,
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setTo($this->getRequest()->getPost('email'), $this->getRequest()->getPost('name'))
                        ->setSubject($this->getTranslator()->trans('automaticEmail'))
                        ->setFrom($this->getConfig()->get('standardMail'), $sitetitle)
                        ->setMessage($message)
                        ->addGeneralHeader('Content-Type', 'text/html; charset="utf-8"');
                    $mail->setAdditionalParameters('-t '.'-f'.$this->getConfig()->get('standardMail'));
                    $mail->send();
                }

                $this->redirect()
                    ->to(['action' => 'finish']);
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'input']);
        }
    }

    public function finishAction()
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('step3to3'), ['action' => 'finish']);

        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'));    
    }

    public function confirmAction()
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuConfirm'), ['action' => 'confirm']);

        $errors = [];

        if ($this->getRequest()->getPost('saveConfirm')) {
            $selector = $this->getRequest()->getParam('selector');
            $confirmedCode = $this->getRequest()->getPost('confirmedCode');

            if (empty($selector)) {
                $errors['selector'] = 'fieldEmpty';
            }

            if (empty($confirmedCode)) {
                $errors['confirmedCode'] = 'fieldEmpty';
            }

            if (empty($errors)) {
                $this->redirect(['controller' => 'regist', 'action' => 'confirm', 'selector' => $selector, 'code' => $confirmedCode]);
            }

            $this->getView()->set('errors', $errors);
        } else {
            $userMapper = new UserMapper();
            $selector = $this->getRequest()->getParam('selector');
            $confirmedCode = $this->getRequest()->getParam('code');
            $user = $userMapper->getUserBySelector($selector);

            if (!empty($confirmedCode) and !empty($selector)) {
                if (!empty($user) and hash_equals($user->getConfirmedCode(), $confirmedCode)) {
                    $currentDate = new \Ilch\Date();
                    $user->setDateConfirmed($currentDate);
                    $user->setConfirmed(1);
                    $user->setConfirmedCode('');
                    $user->setSelector('');
                    $userMapper->save($user);

                    $this->getView()->set('confirmed', '1');
                } else {
                    $this->getView()->set('confirmed', null);

                    $_SESSION['messages'][] = ['text' => $this->getTranslator()->trans('confirmedCodeWrong'), 'type' => 'warning'];
                }
            } else {
                $this->getView();
            }
        }
    }
}


