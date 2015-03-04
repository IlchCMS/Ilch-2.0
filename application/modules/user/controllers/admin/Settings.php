<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Controllers\Admin\Base as BaseController;

defined('ACCESS') or die('no direct access');

class Settings extends BaseController
{
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('regist_accept', $this->getRequest()->getPost('regist_accept'));
            $this->getConfig()->set('regist_confirm', $this->getRequest()->getPost('regist_confirm'));
            $this->getConfig()->set('regist_rules', $this->getRequest()->getPost('regist_rules'));
            $this->getConfig()->set('regist_confirm_mail', $this->getRequest()->getPost('regist_confirm_mail'));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'));
        $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
        $this->getView()->set('regist_confirm_mail', $this->getConfig()->get('regist_confirm_mail'));
    }
}
