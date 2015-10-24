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
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuUserList'), array('action' => 'index'));

        $this->getView()->set('userList', $userMapper->getUserList(array('confirmed' => 1)));
    }    
}


