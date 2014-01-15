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
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewGroup',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'group', 'action' => 'treat', 'id' => 0))
            )
        );
    }

    /**
     * Shows a table with all groups.
     */
    public function indexAction()
    {
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach($this->getRequest()->getPost('check_groups') as $groupId) {
                if ($groupId != 1) {
                    $groupMapper->delete($groupId);
                }
            }
        }
        
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

        if ($groupId && $this->getRequest()->isSecure()) {
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