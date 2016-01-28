<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Boxes;

class Login extends \Ilch\Box
{
    public function render()
    {
        if (isset($_SESSION['redirect'])) {
            $redirectUrl = $_SESSION['redirect'];
        } else {
            $redirectUrl = $this->getRouter()->getQuery();
        }
        $this->getView()->setArray([
            'regist_accept' => $this->getConfig()->get('regist_accept'),
            'redirectUrl' => $redirectUrl
        ]);
    }
}
