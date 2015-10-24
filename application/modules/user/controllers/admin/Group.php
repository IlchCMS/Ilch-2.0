<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Controllers\Admin\Base as BaseController;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\Group as GroupModel;

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
                'url'  => $this->getLayout()->getUrl(array('controller' => 'group', 'action' => 'treat', 'id' => 0))
            )
        );
    }

    /**
     * Shows a table with all groups.
     */
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuGroup'), array('action' => 'index'));

        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach ($this->getRequest()->getPost('check_groups') as $groupId) {
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
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuGroup'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('editGroup'), array('action' => 'treat', 'id' => $this->getRequest()->getParam('id')));

        $groupId = $this->getRequest()->getParam('id');
        $groupMapper = new GroupMapper();

        if ($groupMapper->groupWithIdExists($groupId)) {
            $group = $groupMapper->getGroupById($groupId);
        }
        else {
            $group = new GroupModel();
        }

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
