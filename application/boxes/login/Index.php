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

        if ($this->getRequest()->getPost('loginbox_emailname')) {
            if (\Ilch\Registry::get('user')) {
                $errors['alreadyLoggedIn'] = 'alreadyLoggedIn';
            }

            $emailName = $this->getRequest()->getPost('loginbox_emailname');
            $password = $this->getRequest()->getPost('loginbox_password');

            if (empty($emailName)) {
                $errors['loginbox_emailname'] = 'fieldEmpty';
            }
            
            if (empty($password)) {
                $errors['loginbox_password'] = 'fieldEmpty';
            } else {
                $mapper = new UserMapper();
                $user = $mapper->getUserByEmail($emailName);

                if ($user == null) {
                    $user = $mapper->getUserByName($emailName);
                }

                if ($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('loginbox_password'), $user->getPassword())) {
                    ?>  
                    <script type="text/javascript">
                        top.location.href = '<?php echo $this->getLayout()->url(array('module' => 'user', 'controller' => 'login', 'action' => 'index')); ?>';
                    </script>
                    <?php
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
            $this->getView()->set('errors', $errors);
        }
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
    }
}
