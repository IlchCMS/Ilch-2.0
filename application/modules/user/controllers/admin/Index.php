<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Controllers\Admin\Base as BaseController;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Models\Group as GroupModel;
use \Ilch\Registry as Registry;

/**
 * Handles action for the main admin configuration page.
 */
class Index extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewUser',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    /**
     * Shows a table with all users.
     */
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), array('action' => 'index'));

        $userMapper = new UserMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach($this->getRequest()->getPost('check_users') as $userId) {
                $deleteUser = $userMapper->getUserById($userId);

                if ($deleteUser->getId() != Registry::get('user')->getId()) {
                    if($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() == 1) {} else {
                        $userMapper->delete($deleteUser->getId());
                    }
                }
            }
        }

        $userList = $userMapper->getUserList();
        $this->getView()->set('userList', $userList);
        $this->getView()->set('showDelUserMsg', $this->getRequest()->getParam('showDelUserMsg'));
        $this->getView()->set('errorMsg', $this->getRequest()->getParam('errorMsg'));
    }

    /**
     * Shows a form to create or edit a new user.
     */
    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('editUser'), array('action' => 'treat'));

        $userMapper = new UserMapper();

        if ($this->getRequest()->isPost()) {
            $userData = $this->getRequest()->getPost('user');
            
            if (!empty($userData['password'])) {
                $userData['password'] = crypt($userData['password']);
            }

            $user = $userMapper->loadFromArray($userData);

            if (!empty($userData['groups'])) {
                foreach ($userData['groups'] as $groupId) {
                    $group = new GroupModel();
                    $group->setId($groupId);
                    $user->addGroup($group);
                }
            }

            $date = new \Ilch\Date();
            $user->setDateCreated($date);

            $userId = $userMapper->save($user);

            if (!empty($userId) && empty($userData['id'])) {
                $this->addMessage('newUserMsg');
            }
        }

        if (empty($userId)) {
            $userId = $this->getRequest()->getParam('id');
        }

        if ($userMapper->userWithIdExists($userId)) {
            $user = $userMapper->getUserById($userId);
        }
        else {
            $user = new UserModel();
        }

        $groupMapper = new GroupMapper();

        $this->getView()->set('user', $user);
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }

    /**
     * Deletes the given user.
     */
    public function deleteAction()
    {
        $userMapper = new UserMapper();
        $userId = $this->getRequest()->getParam('id');

        if ($userId && $this->getRequest()->isSecure()) {
            $deleteUser = $userMapper->getUserById($userId);

            /*
             * Admingroup has always id "1" because group is not deletable.
             */
            if ($deleteUser->getId() == Registry::get('user')->getId()) {
                $this->addMessage('delOwnUserProhibited', 'warning');
            } elseif ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
                $this->addMessage('delLastAdminProhibited', 'warning');
                /*
                 * Delete adminuser only if he is not the last admin.
                 */
            } else {
                if ($deleteUser->getAvatar() != 'static/img/noavatar.jpg') {
                    unlink($deleteUser->getAvatar());
                }

                if ($userMapper->delete($userId)) {                    
                    $this->addMessage('delUserMsg');
                }
            }
        }

        $this->redirect(array('action' => 'index'));
    }
}
