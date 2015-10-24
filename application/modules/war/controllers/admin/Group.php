<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Controllers\Admin\Base as BaseController;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Models\Group as GroupModel;
use Modules\User\Mappers\Group as UserGroupMapper;

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
                'url'  => $this->getLayout()->getUrl(array('controller' => 'group', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), array('action' => 'index'));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach($this->getRequest()->getPost('check_groups') as $groupId) {
                $groupMapper->delete($groupId);
            }
        }

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('groups', $groupMapper->getGroupList($pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $groupMapper = new GroupMapper();
        $userGroupMapper = new UserGroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('treatGroup'), array('action' => 'treat'));
            $groups = $groupMapper->getGroupById($this->getRequest()->getParam('id'));
            $this->getView()->set('groups', $groups);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('manageNewGroup'), array('action' => 'treat'));
        }

        $userGroupList = $userGroupMapper->getGroupList();
        $this->getView()->set('userGroupList', $userGroupList);

        if ($this->getRequest()->isPost()) {
            $groupModel = new GroupModel();

            if ($this->getRequest()->getParam('id')) {
                $groupModel->setId($this->getRequest()->getParam('id'));
            }

            $groupName = trim($this->getRequest()->getPost('groupName'));
            $groupTag = trim($this->getRequest()->getPost('groupTag'));
            $groupImage = trim($this->getRequest()->getPost('groupImage'));
            $groupMember = $this->getRequest()->getPost('userGroup');

            if (empty($groupName)) {
                $this->addMessage('missingGroupName', 'danger');
            } elseif(empty($groupImage)) {
                $this->addMessage('missingGroupImage', 'danger');
            } elseif(empty($groupMember)) {
                $this->addMessage('missingGroupMember', 'danger');
            } elseif(empty($groupTag)) {
                $this->addMessage('missingGroupTag', 'danger');
            } else {
                $groupModel->setGroupMember($groupMember);
                $groupModel->setGroupName($groupName);
                $groupModel->setGroupTag($groupTag);
                $groupModel->setGroupImage($groupImage);
                $groupMapper->save($groupModel);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $groupMapper = new GroupMapper();
            $groupMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
