<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Boxes;

use Ilch\Accesses;
use Modules\User\Mappers\AuthProvider;

class Login extends \Ilch\Box
{
    public function render()
    {
        $authProvider = new AuthProvider();

        $redirectUrl = $_SESSION['redirect'] ?? $this->getRouter()->getQuery();

        if ($this->getUser()) {
            $access = new Accesses($this->getRequest());
            $this->getView()->set('userAccesses', $access->hasAccess('Admin'));
        }
        $this->getView()->setArray([
            'providers' => $authProvider->getProviders(),
            'regist_accept' => $this->getConfig()->get('regist_accept'),
            'redirectUrl' => $redirectUrl
        ]);
    }
}
