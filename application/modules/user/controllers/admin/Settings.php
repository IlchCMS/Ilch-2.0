<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

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
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index'])
            ],
            [
                'name' => 'menuAuthProviders',
                'active' => false,
                'icon' => 'fa fa-key',
                'url'  => $this->getLayout()->getUrl(['controller' => 'providers', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuUser',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('regist_accept', $this->getRequest()->getPost('regist_accept'));
            $this->getConfig()->set('regist_confirm', $this->getRequest()->getPost('regist_confirm'));
            $this->getConfig()->set('avatar_height', $this->getRequest()->getPost('avatar_height'));
            $this->getConfig()->set('avatar_width', $this->getRequest()->getPost('avatar_width'));
            $this->getConfig()->set('avatar_size', $this->getRequest()->getPost('avatar_size'));
            $this->getConfig()->set('avatar_filetypes', strtolower($this->getRequest()->getPost('avatar_filetypes')));
            $this->getConfig()->set('usergallery_allowed', $this->getRequest()->getPost('usergallery_allowed'));
            $this->getConfig()->set('usergallery_filetypes', strtolower($this->getRequest()->getPost('usergallery_filetypes')));
            $this->getConfig()->set('regist_rules', $this->getRequest()->getPost('regist_rules'));
            $this->getConfig()->set('user_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'))
            ->set('regist_confirm', $this->getConfig()->get('regist_confirm'))
            ->set('avatar_height', $this->getConfig()->get('avatar_height'))
            ->set('avatar_width', $this->getConfig()->get('avatar_width'))
            ->set('avatar_size', $this->getConfig()->get('avatar_size'))
            ->set('avatar_filetypes', $this->getConfig()->get('avatar_filetypes'))
            ->set('usergallery_allowed', $this->getConfig()->get('usergallery_allowed'))
            ->set('usergallery_filetypes', $this->getConfig()->get('usergallery_filetypes'))
            ->set('regist_rules', $this->getConfig()->get('regist_rules'))
            ->set('picturesPerPage', $this->getConfig()->get('user_picturesPerPage'));
    }
}
