<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Models\AuthToken as AuthTokenModel;
use Modules\User\Service\Password as PasswordService;
use Modules\User\Service\Login as LoginService;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Ilch\Validation;

use Modules\User\Mappers\AuthProvider;

class Login extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuLogin'), ['action' => 'index']);

        $redirectUrl = '';

        if ($this->getRequest()->isPost()) {
            $redirectUrl = $this->getRequest()->getPost('login_redirect_url');

            Validation::setCustomFieldAliases([
                'login_emailname' => 'nameEmail',
                'login_password' => 'password',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'login_emailname' => 'required',
                'login_password' => 'required'
            ]);

            if ($validation->isValid()) {
                $result  = LoginService::factory()->perform($this->getRequest()->getPost('login_emailname'), $this->getRequest()->getPost('login_password'));

                if ($result->isSuccessful()) {
                    $cookieStolenMapper = new CookieStolenMapper();

                    if (!$cookieStolenMapper->containsCookieStolen($result->getUser()->getId())) {
                        $this->addMessage($this->getTranslator()->trans('loginSuccessful'), 'success');
                    } else {
                        // The user receives a strongly worded warning that his cookie might be stolen.
                        $cookieStolenMapper->deleteCookieStolen($result->getUser()->getId());
                        $this->addMessage($this->getTranslator()->trans('cookieStolen'), 'danger');
                    }

                    if ($this->getRequest()->getPost('rememberMe')) {
                        $authTokenModel = new AuthTokenModel();

                        // 9 bytes of random data (base64 encoded to 12 characters) for the selector.
                        // This provides 72 bits of keyspace and therefore 236 bits of collision resistance (birthday attacks)
                        $authTokenModel->setSelector(base64_encode(openssl_random_pseudo_bytes(9)));
                        // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
                        $authenticator = openssl_random_pseudo_bytes(33);
                        // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
                        $authTokenModel->setToken(hash('sha256', $authenticator));
                        $authTokenModel->setUserid($result->getUser()->getId());
                        $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

                        setcookie('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime( '+30 days' ), '/', $_SERVER['SERVER_NAME'], (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'), true);

                        $authTokenMapper = new AuthTokenMapper();
                        $authTokenMapper->addAuthToken($authTokenModel);
                    }
                } else {
                    $this->addMessage($this->getTranslator()->trans($result->getError()), 'warning');
                    $redirectUrl = ['module' => 'user', 'controller' => 'login', 'action' => 'index'];
                }

                $this->redirect($redirectUrl);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        if (!empty($_SESSION['redirect'])) {
            $redirectUrl = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
        }

        $authProvider = new AuthProvider();

        $this->getView()->setArray([
            'providers' => $authProvider->getProviders(),
            'regist_accept' => $this->getConfig()->get('regist_accept'),
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function newpasswordAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('newPassword'), ['action' => 'newpassword']);

        if ($this->getRequest()->getPost('saveNewPassword')) {
            $confirmedCode = $this->getRequest()->getParam('code');
            $selector = $this->getRequest()->getParam('selector');

            if (empty($confirmedCode) or empty($selector)) {
                $this->addMessage('incompleteNewPasswordUrl', 'danger');
            } else {
                $userMapper = new UserMapper();
                $user = $userMapper->getUserBySelector($selector);

                // Compare confirmedCode from the database with the one provided as parameter in the url
                if (!empty($user) and hash_equals($user->getConfirmedCode(), $confirmedCode)) {
                    Validation::setCustomFieldAliases([
                        'password' => 'profileNewPassword',
                        'password2' => 'profileNewPasswordRetype',
                    ]);

                    $validation = Validation::create($this->getRequest()->getPost(), [
                        'password' => 'required|min:6,string|max:30,string',
                        'password2' => 'required|same:password|min:6,string|max:30,string'
                    ]);

                    if ($validation->isValid()) {
                        $user->setConfirmed(1);
                        $user->setConfirmedCode('');
                        $user->setSelector('');
                        $user->setPassword((new PasswordService())->hash($this->getRequest()->getPost('password')));
                        $userMapper->save($user);

                        $this->redirect()
                            ->withMessage('newPasswordSuccess')
                            ->to(['action' => 'index']);
                    } else {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'newpassword', 'selector' => $selector, 'code' => $confirmedCode]);
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
            ->add($this->getTranslator()->trans('menuLogin'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuForgotPassword'), ['action' => 'forgotpassword']);

        if ($this->getRequest()->getPost('saveNewPassword')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'email' => 'required|email',
            ]);

            if ($validation->isValid()) {
                $userMapper = new UserMapper();
                $emailsMapper = new EmailsMapper();
                $email = $this->getRequest()->getPost('email');

                // Do all the work like generating a selector and confirm code even though it might be not needed later
                // if the user was not found. This is done on purpose so both cases take ideally the same time (constant time).
                // This is security relevant!
                $dummy = $userMapper->getDummyUser();
                $user = $userMapper->getUserByEmail($email);
                if ($user == null) {
                    $user = $dummy;
                }

                $selector = bin2hex(openssl_random_pseudo_bytes(9));
                $confirmedCode = bin2hex(openssl_random_pseudo_bytes(32));
                $user->setSelector($selector);
                $user->setConfirmedCode($confirmedCode);
                if ($user->getId()) {
                    // TODO: Ideally call save() for a dummy user too. Currently not possible.
                    $userMapper->save($user);
                }

                $name = $user->getName();
                $sitetitle = $this->getConfig()->get('page_title');
                $siteurl = '<a href="'.BASE_URL.'"><i>'.$sitetitle.'</i></a>';
                $confirmCode = '<a href="'.BASE_URL.'/index.php/user/login/newpassword/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirmMailButtonText').'</a>';
                $date = new \Ilch\Date();

                $layout = '';
                if (!empty($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($user->getId()) {
                    // User found. Send email with the link to change the password.
                    $type = 'password_change_mail';
                } else {
                    // User not found. Still send an email, but regarding the user or someone else tried to reset the password.
                    $type = 'password_change_fail_mail';
                }

                $mailContent = $emailsMapper->getEmail('user', $type, $user->getLocale());

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/passwordchange.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/passwordchange.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/passwordchange.php');
                }

                $messageReplace = [
                    '{content}' => $mailContent->getText(),
                    '{sitetitle}' => $sitetitle,
                    '{date}' => $date->format("l, d. F Y", true),
                    '{name}' => $name,
                    '{siteurl}' => $siteurl,
                    '{remoteaddr}' => $_SERVER['REMOTE_ADDR'],
                    '{confirm}' => $confirmCode,
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($this->getConfig()->get('page_title'))
                    ->setFromEmail($this->getConfig()->get('standardMail'))
                    ->setToName($name)
                    ->setToEmail($email)
                    ->setSubject($this->getTranslator()->trans('automaticEmail'))
                    ->setMessage($message)
                    ->sent();

                $this->addMessage('newPasswordEMailSuccess');
                $this->redirect()
                    ->to(['action' => 'forgotpassword']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'forgotpassword']);
        }
    }
}
