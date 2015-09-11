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
            $this->getConfig()->set('avatar_height', $this->getRequest()->getPost('avatar_height'));
            $this->getConfig()->set('avatar_width', $this->getRequest()->getPost('avatar_width'));
            $this->getConfig()->set('avatar_size', $this->getRequest()->getPost('avatar_size'));
            $this->getConfig()->set('avatar_filetypes', $this->getRequest()->getPost('avatar_filetypes'));
            $this->getConfig()->set('regist_rules', $this->getRequest()->getPost('regist_rules'));
            $this->getConfig()->set('regist_confirm_mail', $this->getRequest()->getPost('regist_confirm_mail'));
            $this->getConfig()->set('password_change_mail', $this->getRequest()->getPost('password_change_mail'));
            $this->getConfig()->set('facebook_login', $this->getRequest()->getPost('facebook_login'));
            $this->getConfig()->set('google_login', $this->getRequest()->getPost('google_login'));
            $this->getConfig()->set('twitter_login', $this->getRequest()->getPost('twitter_login'));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
        $this->getView()->set('regist_confirm', $this->getConfig()->get('regist_confirm'));
        $this->getView()->set('avatar_height', $this->getConfig()->get('avatar_height'));
        $this->getView()->set('avatar_width', $this->getConfig()->get('avatar_width'));
        $this->getView()->set('avatar_size', $this->getConfig()->get('avatar_size'));
        $this->getView()->set('avatar_filetypes', $this->getConfig()->get('avatar_filetypes'));
        $this->getView()->set('regist_rules', $this->getConfig()->get('regist_rules'));
        $this->getView()->set('regist_confirm_mail', $this->getConfig()->get('regist_confirm_mail'));
        $this->getView()->set('password_change_mail', $this->getConfig()->get('password_change_mail'));
        $this->getView()->set('facebook_login', $this->getConfig()->get('facebook_login'));
        $this->getView()->set('google_login', $this->getConfig()->get('google_login'));
        $this->getView()->set('twitter_login', $this->getConfig()->get('twitter_login'));
    }
}
