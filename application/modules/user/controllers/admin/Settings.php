<?php
/**
 * @copyright Ilch 2
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
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index'])
            ],
            [
                'name' => 'menuAuthProviders',
                'active' => false,
                'icon' => 'fa-solid fa-key',
                'url'  => $this->getLayout()->getUrl(['controller' => 'providers', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuUser',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('userGroupList_allowed') == 1) {
                $this->getConfig()->set('userGroupList_allowed', 1);
                $this->addMessage('saveSuccess');
            } else {
                $this->getConfig()->set('userGroupList_allowed', 0);
            }

            if ($this->getRequest()->getPost('userAvatarList_allowed') == 1) {
                $this->getConfig()->set('userAvatarList_allowed', 1);
                $this->addMessage('saveSuccess');
            } else {
                $this->getConfig()->set('userAvatarList_allowed', 0);
            }

            // Don't allow adding forbidden file extensions.
            $forbiddenExtensionFound = false;
            $extensionLists = ['avatar_filetypes', 'usergallery_filetypes'];
            $extensionBlacklist = explode(' ', $this->getConfig()->get('media_extensionBlacklist'));
            foreach ($extensionLists as $targetList) {
                $list = explode(' ', strtolower($this->getRequest()->getPost($targetList)));
                if (is_in_array($extensionBlacklist, $list)) {
                    $forbiddenExtensionFound = true;
                    break;
                }
            }

            if ($forbiddenExtensionFound) {
                $this->addMessage('forbiddenExtension', 'danger');
            } else {
                if ($this->getRequest()->getPost('regist_confirm') == 1) {
                    $this->getConfig()->set('regist_setfree', 0);
                } else {
                    $this->getConfig()->set('regist_setfree', $this->getRequest()->getPost('regist_setfree'));
                }
                if ($this->getRequest()->getPost('delete_time') == '') {
                    $this->getConfig()->set('userdeletetime', 0);
                } else {
                    $this->getConfig()->set('userdeletetime', $this->getRequest()->getPost('delete_time'));
                }

                $this->getConfig()->set('regist_accept', $this->getRequest()->getPost('regist_accept'))
                    ->set('regist_confirm', $this->getRequest()->getPost('regist_confirm'))
                    ->set('avatar_height', $this->getRequest()->getPost('avatar_height'))
                    ->set('avatar_width', $this->getRequest()->getPost('avatar_width'))
                    ->set('avatar_size', $this->getRequest()->getPost('avatar_size'))
                    ->set('avatar_filetypes', strtolower($this->getRequest()->getPost('avatar_filetypes')))
                    ->set('usergallery_allowed', $this->getRequest()->getPost('usergallery_allowed'))
                    ->set('usergallery_filetypes', strtolower($this->getRequest()->getPost('usergallery_filetypes')))
                    ->set('regist_rules', $this->getRequest()->getPost('regist_rules'))
                    ->set('user_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
                $this->addMessage('saveSuccess');
            }
        }

        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'))
            ->set('regist_confirm', $this->getConfig()->get('regist_confirm'))
            ->set('regist_setfree', $this->getConfig()->get('regist_setfree'))
            ->set('avatar_height', $this->getConfig()->get('avatar_height'))
            ->set('avatar_width', $this->getConfig()->get('avatar_width'))
            ->set('avatar_size', $this->getConfig()->get('avatar_size'))
            ->set('avatar_filetypes', $this->getConfig()->get('avatar_filetypes'))
            ->set('usergallery_allowed', $this->getConfig()->get('usergallery_allowed'))
            ->set('usergallery_filetypes', $this->getConfig()->get('usergallery_filetypes'))
            ->set('regist_rules', $this->getConfig()->get('regist_rules'))
            ->set('picturesPerPage', $this->getConfig()->get('user_picturesPerPage'))
            ->set('delete_time', $this->getConfig()->get('userdeletetime'))
            ->set('userGroupList_allowed', $this->getConfig()->get('userGroupList_allowed'))
            ->set('userAvatarList_allowed', $this->getConfig()->get('userAvatarList_allowed'));
    }
}
