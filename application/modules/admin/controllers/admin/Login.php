<?php
/**
 * Holds class Login.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
use User\Mappers\User as UserMapper;
defined('ACCESS') or die('no direct access');

/**
 * Handles the login functionality.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Login extends \Ilch\Controller\Admin
{
    /**
     * Sets the layout file for this controller.
     */
    public function init()
    {
        $this->getLayout()->setFile('modules/admin/login');
    }

    /**
     * Shows the standard login page.
     * %akes the request data for the login and tries to login the user.
     */
    public function indexAction()
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            if (\Ilch\Registry::get('user')) {
                $errors['alreadyLoggedIn'] = 'alreadyLoggedIn';
            }

            $emailName = $this->getRequest()->getPost('emailname');

            if ($emailName === '') {
                $errors['noEmailGiven']  = 'noUserEmailGiven';
            } else {
                $mapper = new UserMapper();
                $user = $mapper->getUserByEmail($emailName);

                if ($user == null) {
                    $user = $mapper->getUserByName($emailName);
                }

                if ($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('password'), $user->getPassword())) {
                    $errors['userNotFound'] = 'userNotFound';
                } else {
                    /*
                     * A use was found. Set his id in the session and redirect to the admincenter.
                     */
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['language'] = $this->getRequest()->getPost('language');
                    $this->redirect(array('controller' => 'index', 'action' => 'index'));
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
        session_destroy();
        unset($_SESSION);
        \Ilch\Registry::remove('user');

        /*
         * @todo flash message helper for show logout message on next site.
         */
        $this->redirect(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'));
    }
}
