<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Service\Password as PasswordService;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
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
                } else {
                    $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'index']);
                }
            } else {
                $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'))
                    ->set('regist_accept', $this->getConfig()->get('regist_accept'));
            }
        } else {
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index']);
        }
    }

    public function inputAction()
    {
        $registMapper = new UserMapper();
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('step2to3'), ['action' => 'input']);

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('bot') === '') {
            Validation::setCustomFieldAliases([
                'grecaptcha' => 'token',
            ]);

            $validationRules = [
                'name' => 'required|unique:users,name',
                'password' => 'required|min:6,string|max:30,string',
                'password2' => 'required|same:password|min:6,string|max:30,string',
                'email' => 'required|email|unique:users,email'
            ];

            if ($captchaNeeded) {
                if (\in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                    $validationRules['token'] = 'required|grecaptcha:saveRegist';
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);
            $emailOnBlacklist = isEmailOnBlacklist($this->getRequest()->getPost('email'));

            if (!$emailOnBlacklist && $validation->isValid()) {
                $groupMapper = new GroupMapper();
                $userGroup = $groupMapper->getGroupById(2);
                $currentDate = new \Ilch\Date();
                $emailsMapper = new EmailsMapper();

                $model = new UserModel();
                if ($this->getConfig()->get('regist_confirm') == 0 && $this->getConfig()->get('regist_setfree') == 0) {
                    $model->setDateConfirmed($currentDate->format('Y-m-d H:i:s', true));
                } else {
                    $selector = bin2hex(random_bytes(9));
                    // 33 bytes instead of 32 bytes just that the confirmedCode to confirm a registration
                    // is different from the one to change a password and therefore can only be used for this purpose.
                    $confirmedCode = bin2hex(random_bytes(33));
                    $model->setSelector($selector)
                        ->setConfirmedCode($confirmedCode)
                        ->setConfirmed(0);
                }
                $model->setName($this->getRequest()->getPost('name'))
                    ->setPassword((new PasswordService())->hash($this->getRequest()->getPost('password')))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setLocale($this->getTranslator()->getLocale())
                    ->setDateCreated($currentDate->format('Y-m-d H:i:s', true))
                    ->addGroup($userGroup);
                $registMapper->save($model);

                $_SESSION['name'] = $this->getRequest()->getPost('name');
                $_SESSION['email'] = $this->getRequest()->getPost('email');

                if ($this->getConfig()->get('regist_setfree') == 1) {
                    $notificationsMapper = new NotificationsMapper();
                    $notificationModel = new NotificationModel();
                    $notificationModel->setModule('user');
                    $notificationModel->setMessage($this->getTranslator()->trans('userAwaitingApproval'));
                    $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'user', 'controller' => 'index', 'action' => 'index', 'showsetfree' => 1], 'admin'));
                    $notificationModel->setType('userAwaitingApproval');
                    $notificationsMapper->addNotification($notificationModel);
                }

                if ($this->getConfig()->get('regist_confirm') == 1) {
                    $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                    $confirmCode = '<a href="'.BASE_URL.'/index.php/user/regist/confirm/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirmMailButtonText').'</a>';
                    $date = new \Ilch\Date();
                    $mailContent = $emailsMapper->getEmail('user', 'regist_confirm_mail', $this->getTranslator()->getLocale());
                    $name = $this->getLayout()->escape($this->getRequest()->getPost('name'));

                    $layout = $_SESSION['layout'] ?? '';

                    if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/registconfirm.php')) {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/registconfirm.php');
                    } else {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/registconfirm.php');
                    }
                    $messageReplace = [
                        '{content}' => $this->getLayout()->purify($mailContent->getText()),
                        '{sitetitle}' => $siteTitle,
                        '{date}' => $date->format('l, d. F Y', true),
                        '{name}' => $name,
                        '{confirm}' => $confirmCode,
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setFromName($siteTitle)
                        ->setFromEmail($this->getConfig()->get('standardMail'))
                        ->setToName($name)
                        ->setToEmail($this->getRequest()->getPost('email'))
                        ->setSubject($this->getTranslator()->trans('automaticEmail'))
                        ->setMessage($message)
                        ->send();
                }

                $this->redirect()
                    ->to(['action' => 'finish']);
            } else {
                if ($emailOnBlacklist) {
                    $this->addMessage('emailOnBlacklist', 'danger');
                }
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'input']);
            }
        }

        if ($captchaNeeded) {
            if (\in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                $googlecaptcha = new \Captcha\GoogleCaptcha($this->getConfig()->get('captcha_apikey'), null, (int)$this->getConfig()->get('captcha'));
                $this->getView()->set('googlecaptcha', $googlecaptcha);
            } else {
                $defaultcaptcha = new \Captcha\DefaultCaptcha();
                $this->getView()->set('defaultcaptcha', $defaultcaptcha);
            }
        }
        $this->getView()->set('captchaNeeded', $captchaNeeded);
    }

    public function finishAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('step3to3'), ['action' => 'finish']);

        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'))
            ->set('regist_setfree', $this->getConfig()->get('regist_setfree'));
    }

    public function confirmAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuRegist'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuConfirm'), ['action' => 'confirm']);

        $userMapper = new UserMapper();
        $selector = $this->getRequest()->getParam('selector');
        $confirmedCode = $this->getRequest()->getParam('code');
        $user = $userMapper->getUserBySelector($selector);

        if (!empty($confirmedCode) && !empty($selector)) {
            if (!empty($user) && hash_equals($user->getConfirmedCode(), $confirmedCode)) {
                $currentDate = new \Ilch\Date();
                $user->setDateConfirmed($currentDate);
                $user->setConfirmed(1);
                $user->setConfirmedCode('');
                $user->setSelector('');
                $user->setAvatar('');
                $userMapper->save($user);

                $this->redirect()
                    ->withMessage('accountApproved')
                    ->to([]);
            } else {
                $this->redirect()
                    ->withMessage('confirmedCodeWrong', 'warning')
                    ->to([]);
            }
        } else {
            $this->redirect()
                ->withMessage('incompleteActivationUrl', 'warning')
                ->to([]);
        }
    }
}


