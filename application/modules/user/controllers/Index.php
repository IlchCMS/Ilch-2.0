<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers;

use User\Mappers\User as UserMapper;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
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
        ?>
        <script language="JavaScript" type="text/javascript">
            window.location = document.referrer;
        </script>
        <?php
    }
}
?>