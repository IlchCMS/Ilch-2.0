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
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuComments',
            $items
        );
    }
    
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuComments'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('comment_reply', $this->getRequest()->getPost('reply'));
            $this->getConfig()->set('comment_nesting', $this->getRequest()->getPost('nesting'));
            $this->getConfig()->set('comment_avatar', strlen($this->getRequest()->getPost('check_avatar')));
            $this->getConfig()->set('comment_date', strlen($this->getRequest()->getPost('check_date')));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('comment_reply', $this->getConfig()->get('comment_reply'));
        $this->getView()->set('comment_nesting', $this->getConfig()->get('comment_nesting'));
        $this->getView()->set('comment_avatar', $this->getConfig()->get('comment_avatar'));
        $this->getView()->set('comment_date', $this->getConfig()->get('comment_date'));
    }
}
