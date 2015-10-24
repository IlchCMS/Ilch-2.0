<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Boxes;

use Modules\User\Mappers\User as UserMapper;

class Login extends \Ilch\Box
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
            } elseif (empty($password)) {
                $errors['loginContent_password'] = 'fieldEmpty';
            } else {
                $mapper = new UserMapper();
                $user = $mapper->getUserByEmail($emailName);

                if ($user == null) {
                    $user = $mapper->getUserByName($emailName);
                }

                if ($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('loginbox_password'), $user->getPassword())) {
                    $_SESSION['messages'][] = array('text' => 'Sie haben einen fehlerhaften Benutzername, E-Mail oder Passwort angegeben. Bitte prüfen Sie ihre Angaben und versuche Sie es erneut.', 'type' => 'warning');

                    ?>  
                    <script type="text/javascript">
                        top.location.href = '<?=$this->getLayout()->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'index')) ?>';
                    </script>
                    <?php
                } elseif ($user->getConfirmed() == 0) {
                    $_SESSION['messages'][] = array('text' => 'Benutzer nicht freigeschaltet! Bitte bestätigen Sie ihren Account in der verschickten E-Mail oder fordern Sie eine neue E-Mail mit einen Freischaltlink an.', 'type' => 'warning');

                    ?>  
                    <script type="text/javascript">
                        top.location.href = '<?=$this->getLayout()->getUrl(array('module' => 'user', 'controller' => 'login', 'action' => 'index')) ?>';
                    </script>
                    <?php
                } else {
                    $_SESSION['user_id'] = $user->getId();

                    $_SESSION['messages'][] = array('text' => 'Sie haben sich erfolgreich eingeloggt.', 'type' => 'success');
                    ?>

                    <script type="text/javascript">
                        top.location.href = '<?=$this->getLayout()->getUrl() ?>';
                    </script>
                    <?php
                }
            }

            $this->getView()->set('errors', $errors);
        }

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
    }
}
