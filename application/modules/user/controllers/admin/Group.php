<?php
/**
 * Holds the class Index.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Controllers\Admin;

use User\Controllers\Admin\Base as BaseController;
use User\Mappers\User as UserMapper;
use User\Mappers\Group as GroupMapper;
use User\Models\User as UserModel;
use User\Models\Group as GroupModel;
use \Ilch\Registry as Registry;

defined('ACCESS') or die('no direct access');

/**
 * Handles action for the main admin configuration page.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Group extends BaseController
{
    /**
     * Shows a table with all groups.
     */
    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $groupList = $groupMapper->getGroupList();
        $groupUsers = array();

        foreach ($groupList as $group) {
            $groupUsers[$group->getId()] = $groupMapper->getUsersForGroup($group->getId());
        }

        $this->getView()->set('groupUsersList', $groupUsers);
        $this->getView()->set('groupList', $groupList);
        $this->getView()->set('showDelGroupMsg', $this->getRequest()->getParam('showDelGroupMsg'));
        $this->getView()->set('errorMsg', $this->getRequest()->getParam('errorMsg'));
    }

    /**
     * Shows a form to create or edit a new group.
     */
    public function treatAction()
    {
        $groupId = $this->getRequest()->getParam('id');
        $groupMapper = new GroupMapper();

        if ($groupMapper->groupWithIdExists($groupId)) {
            $group = $groupMapper->getGroupById($groupId);
        }
        else {
            $group = new GroupModel();
        }

        $groupMapper = new GroupMapper();

        $this->getView()->set('group', $group);
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }

    /**
     * Saves the given group.
     */
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['group'])) {
            $groupData = $postData['group'];

            $groupMapper = new GroupMapper();
            $group = $groupMapper->loadFromArray($groupData);
            $groupId = $groupMapper->save($group);

            if (!empty($groupId) && empty($groupData['id'])) {
                $this->addMessage('newGroupMsg');
            }

            $this->redirect(array('action' => 'treat', 'id' => $groupId));
        }
    }

    /**
     * Deletes the given group.
     */
    public function deleteAction()
    {
        $groupMapper = new GroupMapper();
        $groupId = $this->getRequest()->getParam('id');

        if ($groupId) {
            $deletegroup = $groupMapper->getGroupById($groupId);
            $usersForGroup = $groupMapper->getUsersForGroup($groupId);

            /*
             * Admingroup has always id "1" and is not allowed to be deleted.
             */
            if ($groupId == 1) {
                $this->addMessage('delAdminGroup', 'warning');
            } else {
                if ($groupMapper->delete($groupId)) {
                    $this->addMessage('delGroupMsg');
                }
            }
        }

        $this->redirect(array('action' => 'index'));
    }
}