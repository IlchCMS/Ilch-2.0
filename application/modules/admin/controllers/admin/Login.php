<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\User\Mappers\AuthToken;
use Modules\User\Service\Login as LoginService;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;

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
        $errors = [];

        if ($this->getRequest()->isPost()) {
            if (\Ilch\Registry::get('user')) {
                $errors[] = 'alreadyLoggedIn';
            }

            $emailName = $this->getRequest()->getPost('emailname');

            if (!is_string($emailName) || empty($emailName)) {
                $errors[] = 'noUserEmailGiven';
            } else {
                $password = $this->getRequest()->getPost('password');
                $language = $this->getRequest()->getPost('language');

                if (is_string($language) && !empty($language)) {
                    $_SESSION['language'] = $language;
                    $this->getTranslator()->setLocale($language, true);
                }

                $result = LoginService::factory()->perform($emailName, $password);

                if ($result->isSuccessful()) {
                    $this->redirect(['controller' => 'index', 'action' => 'index']);
                } else {
                    $errors[] = $result->getError();
                }
            }

            $this->getLayout()->set('emailname', $emailName);
        }

        $this->getLayout()->set('errors', $errors);
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
            setcookie('remember', '', time() - 3600, '/', $_SERVER['SERVER_NAME'], (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'), true);
        }

        if ($this->getUser()) {
            $statisticMapper->deleteUserOnline($this->getUser()->getId());
        }

        $_SESSION = [];
        \Ilch\Registry::remove('user');

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'],
                $params['domain'], $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        if ($this->getRequest()->getParam('from_frontend')) {
            $this->redirect([]);
        } else {
            $this->redirect(['module' => 'admin', 'controller' => 'login', 'action' => 'index']);
        }
    }
}
