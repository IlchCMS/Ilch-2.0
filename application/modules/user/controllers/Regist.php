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
        if ($this->getConfig()->get('regist_accept') == 1) {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('step1to3'), ['action' => 'index']);

            if ($this->getRequest()->getPost('saveRegist')) {
                if ($this->getRequest()->getPost('acceptRule') == 1) {
                    $this->redirect(['action' => 'input']);
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
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index']);

            $this->getView();
        }
    }

    public function inputAction()
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('step2to3'), ['action' => 'input']);

        $registMapper = new UserMapper();
        $errors = [];

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
                    $user = new \Modules\User\Models\User();
                    $user->setName($name);
                    $user->setPassword((new PasswordService())->hash($password));
                    $user->setEmail($email);
                    $user->setDateCreated($currentDate->format("Y-m-d H:i:s", true));
                    $user->addGroup($userGroup);

                if ($this->getConfig()->get('regist_confirm') == 0) {
                    $user->setDateConfirmed($currentDate->format("Y-m-d H:i:s", true));
                } else {
                    $selector = bin2hex(openssl_random_pseudo_bytes(9));
                    // 33 bytes instead of 32 bytes just that the confirmedCode to confirm a registration
                    // is different from the one to change a password and therefore can only be used for this purpose.
                    $confirmedCode = bin2hex(openssl_random_pseudo_bytes(33));
                    $user->setSelector($selector);
                    $user->setConfirmedCode($confirmedCode);
                    $user->setConfirmed(0);
                }
                $registMapper->save($user);

                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

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
                            '{name}' => $name,
                            '{confirm}' => $confirmCode,
                            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setTo($email, $name)
                            ->setSubject($this->getTranslator()->trans('automaticEmail'))
                            ->setFrom($this->getTranslator()->trans('automaticEmail'), $sitetitle)
                            ->setMessage($message)
                            ->addGeneralHeader('Content-Type', 'text/html; charset="utf-8"');
                    $mail->setAdditionalParameters('-f '.$this->getConfig()->get('standardMail'));
                    $mail->send();
                }

                $this->redirect(['action' => 'finish']);
            }

            $this->getView()->set('errors', $errors);
        }

        $this->getView();
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

                    $_SESSION['messages'][] = ['text' => $this->getTrans('confirmedCodeWrong'), 'type' => 'warning'];
                }
            } else {
                $this->getView();
            }
        }
    }
}


