<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Validation;
use Modules\User\Mappers\AuthToken;
use Modules\User\Service\Login as LoginService;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\User\Service\Remember as RememberMe;

/**
 * Handles the login functionality.
 */
class Login extends \Ilch\Controller\Admin
{
    /**
     * Sets the layout file for this controller.
     */
    public function init()
    {
        $this->getLayout()->setFile('modules/admin/layouts/login');
    }

    /**
     * Shows the standard login page.
     * Takes the request data for the login and tries to login the user.
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            if (\Ilch\Registry::get('user')) {
                $this->redirect(['controller' => 'index', 'action' => 'index']);
            }

            $validation = Validation::create($this->getRequest()->getPost(), [
                'emailname' => 'required',
                'password' => 'required',
                'language' => 'required',
            ]);

            if ($validation->isValid()) {
                $language = $this->getRequest()->getPost('language');

                if (is_string($language) && !empty($language) && $language !== 'default') {
                    $_SESSION['language'] = $language;
                    $this->getTranslator()->setLocale($language, true);
                }

                $result = LoginService::factory()->perform($this->getRequest()->getPost('emailname'), $this->getRequest()->getPost('password'));

                if ($result->isSuccessful()) {
                    if ($this->getRequest()->getPost('rememberMe')) {
                        $rememberMe = new RememberMe();
                        $rememberMe->rememberMe($result->getUser()->getId());
                    }

                    if ($result->getError() != '') {
                        $this->addMessage($this->getTranslator()->trans($result->getError()), 'warning');
                    }
                    $this->redirect(['controller' => 'index', 'action' => 'index']);
                } else {
                    $this->addMessage($this->getTranslator()->trans($result->getError()), 'warning');
                    $this->redirect()
                        ->to(['action' => 'index']);
                }
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getLayout()->set('languages', $this->getTranslator()->getLocaleList());
    }

    /**
     * Does the logout for a user.
     */
    public function logoutAction()
    {
        $statisticMapper = new StatisticMapper();

        if (!empty($_COOKIE['remember'])) {
            list($selector) = explode(':', $_COOKIE['remember']);
            $authTokenMapper = new AuthToken();
            $authTokenMapper->deleteAuthToken($selector);
            setcookieIlch('remember', '', strtotime('-1 hours'));
        }

        if ($this->getUser()) {
            $statisticMapper->deleteUserOnline($this->getUser()->getId());
        }

        $_SESSION = [];
        \Ilch\Registry::remove('user');

        if (ini_get('session.use_cookies')) {
            setcookieIlch(session_name(), '', strtotime('-12 hours'));
        }

        session_destroy();

        if ($this->getRequest()->getParam('from_frontend')) {
            $this->redirect([]);
        } else {
            $this->redirect(['module' => 'admin', 'controller' => 'login', 'action' => 'index']);
        }
    }
}
