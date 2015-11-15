<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\User\Service\Login as LoginService;

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
        $errors = array();

        if ($this->getRequest()->isPost()) {
            if (\Ilch\Registry::get('user')) {
                $errors[] = 'alreadyLoggedIn';
            }

            $emailName = $this->getRequest()->getPost('emailname');

            if ($emailName === '') {
                $errors[] = 'noUserEmailGiven';
            } else {
                $password = $this->getRequest()->getPost('password');
                $language = $this->getRequest()->getPost('language');

                if (!empty($language)) {
                    $_SESSION['language'] = $language;
                    $this->getTranslator()->setLocale($language, true);
                }

                $result = LoginService::factory()->perform($emailName, $password);

                if ($result->isSuccessful()) {
                    $this->redirect(array('controller' => 'index', 'action' => 'index'));
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
        unset($_SESSION['user_id']);
        \Ilch\Registry::remove('user');

        if ($this->getRequest()->getParam('from_frontend')) {
            $this->redirect(array());
        } else {
            $this->redirect(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'));
        }
    }
}
