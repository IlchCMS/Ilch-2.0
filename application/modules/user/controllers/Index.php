<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers;

use User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuUserList'), array('action' => 'index'));

        $userList = $userMapper->getUserList();
        $this->getView()->set('userList', $userList);
    }    
}


