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
use \Ilch\Registry as Registry;

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
        $this->getView()->set('errorMsg', $this->getRequest()->getParam('errorMsg'));
    }

    /**
     * Shows a form to create or edit a new user.
     */
    public function treatAction()
    {
        $userId = $this->getRequest()->getParam('id');
        $userMapper = new UserMapper();

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

            if (!empty($userData['groups'])) {
                foreach ($userData['groups'] as $groupId) {
                    $group = new GroupModel();
                    $group->setId($groupId);
                    $user->addGroup($group);
                }
            }

            $userId = $userMapper->save($user);

            if (!empty($userId) && empty($userData['id'])) {
                $this->addMessage($this->getTranslator()->trans('newUserMsg'));
            }

            $this->redirect(array('action' => 'treat', 'id' => $userId));
        }
    }

    /**
     * Deletes the given user.
     */
    public function deleteAction()
    {
        $userMapper = new UserMapper();
        $userId = $this->getRequest()->getParam('id');

        if ($userId) {
            $deleteUser = $userMapper->getUserById($userId);

            /*
             * Admingroup has always id "1" because group is not deletable.
             */
            if ($deleteUser->getId() == Registry::get('user')->getId()) {
                $this->addMessage($this->getTranslator()->trans('delOwnUserProhibited'), 'warning');
            } elseif ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
                $this->addMessage($this->getTranslator()->trans('delLastAdminProhibited'), 'warning');
                /*
                 * Delete adminuser only if he is not the last admin.
                 */
            } else {
                if ($userMapper->delete($userId)) {
                    $this->addMessage($this->getTranslator()->trans('delUserMsg'));
                }
            }
        }

        $this->redirect(array('action' => 'index'));
    }
}