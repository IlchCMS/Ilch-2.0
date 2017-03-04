<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Models\Group as GroupModel;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Group extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index']),
                [
                    'name' => 'menuActionNewGroup',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach ($this->getRequest()->getPost('check_groups') as $groupId) {
                $groupMapper->delete($groupId);
            }
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_groupsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_groupsPerPage'));
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
                ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatGroup'), ['action' => 'treat']);
            $groups = $groupMapper->getGroupById($this->getRequest()->getParam('id'));
            $this->getView()->set('groups', $groups);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewGroup'), ['action' => 'treat']);
        }

        $userGroupList = $userGroupMapper->getGroupList();
        $this->getView()->set('userGroupList', $userGroupList);

        $post = [
            'groupName' => '',
            'groupTag' => '',
            'groupImage' => '',
            'userGroup' => '',
            'groupDesc' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $groupImage = trim($this->getRequest()->getPost('groupImage'));
            if (!empty($groupImage)) {
                $groupImage = BASE_URL.'/'.$groupImage;
            }

            $post = [
                'groupName' => trim($this->getRequest()->getPost('groupName')),
                'groupTag' => trim($this->getRequest()->getPost('groupTag')),
                'groupImage' => $groupImage,
                'userGroup' => $this->getRequest()->getPost('userGroup'),
                'groupDesc' => trim($this->getRequest()->getPost('groupDesc')),
            ];

            $validation = Validation::create($post, [
                'groupName' => 'required',
                'groupTag' => 'required',
                'groupImage' => 'required|url',
                'userGroup' => 'required|numeric|integer|min:1'
            ]);

            $post['groupImage'] = trim($this->getRequest()->getPost('groupImage'));

            if ($validation->isValid()) {
                $groupModel = new GroupModel();

                if ($this->getRequest()->getParam('id')) {
                    $groupModel->setId($this->getRequest()->getParam('id'));
                }

                $groupModel->setGroupMember($post['userGroup']);
                $groupModel->setGroupName($post['groupName']);
                $groupModel->setGroupTag($post['groupTag']);
                $groupModel->setGroupImage($post['groupImage']);
                $groupModel->setGroupDesc($post['groupDesc']);
                $groupMapper->save($groupModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $groupMapper = new GroupMapper();
            $groupMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
