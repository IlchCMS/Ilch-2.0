<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Login;

use User\Mappers\User as UserMapper;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    /**
     * Shows the standard login page.
     * %akes the request data for the login and tries to login the user.
     */
    public function render()
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

                    ?>
                    <script language="JavaScript" type="text/javascript">
                        window.location.href = "index";
                    </script>
                    <?php
                }
            }

            $this->getLayout()->set('emailname', $emailName);
        }

        $this->getLayout()->set('errors', $errors);
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
        $this->redirect(array('action' => 'index'));
    }
}
?>