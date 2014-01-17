<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers\Admin;

use User\Controllers\Admin\Base as BaseController;

defined('ACCESS') or die('no direct access');

class Settings extends BaseController
{
    public function indexAction() 
    {
        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('regist_accept', $this->getRequest()->getPost('regist_accept'));
            $this->getConfig()->set('regist_confirm', $this->getRequest()->getPost('regist_confirm'));
            $this->getConfig()->set('regist_password', $this->getRequest()->getPost('regist_password'));
            $this->getConfig()->set('regist_rules', $this->getRequest()->getPost('regist_rules'));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'));
        $this->getView()->set('regist_password', $this->getConfig()->get('regist_password'));
        $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
    }
}
