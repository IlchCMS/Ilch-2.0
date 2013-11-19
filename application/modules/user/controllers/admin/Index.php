<?php
/**
 * Holds the class Index.
 *
 * @author Martin Jainta
 * @copyright Ilch Pluto
 * @package ilch
 */

namespace User\Controllers\Admin;
use User\Mappers\User as UserMapper;
use User\Mappers\Group as GroupMapper;
use User\Models\User as UserModel;
use User\Models\Group as GroupModel;

defined('ACCESS') or die('no direct access');

/**
 * Handles action for the main admin configuration page.
 *
 * @author Martin Jainta
 * @copyright Ilch Pluto
 * @package ilch
 */
class Index extends \Ilch\Controller\Admin
{
    /**
     * Initializes the menu.
     */
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuUser',
            array
            (
                array
                (
                    'name' => 'menuUser',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewUser',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat', 'id' => 0))
            )
        );
    }

    /**
     * Shows a table with all users.
     */
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $userList = $userMapper->getUserList();
        $this->getView()->set('userList', $userList);
        $this->getView()->set('showDelUserMsg', $this->getRequest()->getParam('showDelUserMsg'));
    }

    /**
     * Shows a form to create or edit a new user.
     */
    public function treatAction()
    {
        $this->getView()->set('showNewUserMsg', $this->getRequest()->getParam('showNewUserMsg'));
        $userId = $this->getRequest()->getParam('id');
        $userMapper = new UserMapper();

        if ($userMapper->userWithIdExists($userId)) {
            $user = $userMapper->getUserById($userId);
        }
        else {
            $user = new UserModel();
        }

        $this->getView()->set('user', $user);

        $groupMapper = new GroupMapper();
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }

    /**
     * Saves the given user.
     */
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['user'])) {
            $userData = $postData['user'];

            $userMapper = new UserMapper();
            $user = $userMapper->loadFromArray($userData);
            $user->setDateCreated(time());

            foreach ($userData['groups'] as $groupId) {
                $group = new GroupModel();
                $group->setId($groupId);
                $user->addGroup($group);
            }

            $userId = $userMapper->save($user);
            $this->redirect(array('action' => 'treat', 'id' => $userId, 'showNewUserMsg' => (int)isset($postData['user']['id'])));
        }
    }

    /**
     * Deletes the given user.
     */
    public function deleteAction()
    {
        $userMapper = new UserMapper();
        $userId = $this->getRequest()->getParam('id');
        $success = false;

        if ($userId) {
            $deleteUser = $userMapper->getUserById($userId);

            /*
             * Admingroup has always id "1" because group is not deletable.
             */
            if ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
               /*
                * Delete adminuser only if he is not the last admin.
                * @todo create message for frontend.
                */
            } else {
                $success = $userMapper->delete($userId);
            }
        }

        $this->redirect(array('action' => 'index', 'showDelUserMsg' => (int)$success));
    }
}