<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuComments',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }
    
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('comment_reply', $this->getRequest()->getPost('reply'));
            $this->getConfig()->set('comment_interleaving', $this->getRequest()->getPost('interleaving'));
            $this->getConfig()->set('comment_avatar', strlen($this->getRequest()->getPost('check_avatar')));
            $this->getConfig()->set('comment_date', strlen($this->getRequest()->getPost('check_date')));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('comment_reply', $this->getConfig()->get('comment_reply'));
        $this->getView()->set('comment_interleaving', $this->getConfig()->get('comment_interleaving'));
        $this->getView()->set('comment_avatar', $this->getConfig()->get('comment_avatar'));
        $this->getView()->set('comment_date', $this->getConfig()->get('comment_date'));
    }
}
