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
        $this->getView()->setArray([
            'regist_accept' => $this->getConfig()->get('regist_accept'),
            'redirectUrl' => $this->getRouter()->getQuery()
        ]);
    }
}
