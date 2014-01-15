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
                    $_SESSION['user_id'] = $user->getId();

                    ?>
                    <script type="text/javascript">
                        top.location.href = '<?php echo $this->getLayout()->url(); ?>';
                    </script>
                    <?php
                    exit;
                }
            }

            $this->getLayout()->set('emailname', $emailName);
        }
        
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
        
        $this->getLayout()->set('errors', $errors);
    }
}
