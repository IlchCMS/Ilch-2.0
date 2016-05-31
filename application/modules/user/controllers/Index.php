<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), ['action' => 'index']);

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('userList', $userMapper->getUserList(['confirmed' => 1]));
    }    
}


