<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Pagination;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Models\Group as GroupModel;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Group extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa-solid fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index']),
                [
                    'name' => 'menuActionNewGroup',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcons',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $pagination = new Pagination();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach ($this->getRequest()->getPost('check_groups') as $groupId) {
                $groupMapper->delete($groupId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_groupsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_groupsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('groups', $groupMapper->getGroupList($pagination))
            ->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $groupMapper = new GroupMapper();
        $userGroupMapper = new UserGroupMapper();
        $groupModel = new GroupModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatGroup'), ['action' => 'treat']);

            $groupModel = $groupMapper->getGroupById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageGroups'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewGroup'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $groupImage = $this->getRequest()->getPost('groupImage');
            if (!empty($groupImage)) {
                $groupImage = BASE_URL . '/' . $groupImage;
            }
            
            $post = [
                'groupName' => $this->getRequest()->getPost('groupName'),
                'groupTag' => $this->getRequest()->getPost('groupTag'),
                'groupImage' => $groupImage,
                'userGroup' => $this->getRequest()->getPost('userGroup')
            ];
            
            $validator = [
                'groupName' => 'required|unique:war_groups,name',
                'groupTag' => 'required|unique:war_groups,tag',
                'groupImage' => 'required|url',
                'userGroup' => 'required|numeric|integer|min:1|exists:groups'
            ];
            
            if ($groupModel->getId()) {
                $validator['groupName'] = 'required';
                $validator['groupTag'] = 'required';
            }

            $validation = Validation::create($post, $validator);

            if ($validation->isValid()) {
                $groupModel->setGroupMember($this->getRequest()->getPost('userGroup'))
                    ->setGroupName($this->getRequest()->getPost('groupName'))
                    ->setGroupTag($this->getRequest()->getPost('groupTag'))
                    ->setGroupImage($this->getRequest()->getPost('groupImage'))
                    ->setGroupDesc($this->getRequest()->getPost('groupDesc'));
                $groupMapper->save($groupModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($groupModel->getId()?['id' => $groupModel->getId()]:[])));
        }

        $this->getView()->set('groups', $groupModel)
            ->set('userGroupList', $userGroupMapper->getGroupList());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $groupMapper = new GroupMapper();
            $groupMapper->delete((int)$this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
