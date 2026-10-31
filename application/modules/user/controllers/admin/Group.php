<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\Group as GroupModel;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Pagination;
use Ilch\Validation;

/**
 * Handles action for the main admin configuration page.
 */
class Group extends \Ilch\Controller\Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index']),
                [
                    'name' => 'menuActionNewGroup',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'treat'])
                ],
                [
                    'name' => 'menuAccess',
                    'active' => false,
                    'icon' => 'fa-solid fa-scale-balanced',
                    'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'access'])
                ]
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
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'access') {
            $items[1][1]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuUser',
            $items
        );
    }

    /**
     * Shows a table with all groups.
     */
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroup'), ['action' => 'index']);

        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach ($this->getRequest()->getPost('check_groups') as $groupId) {
                if ($groupId != 1 && $groupId != 2 && $groupId != 3) {
                    $groupMapper->delete($groupId);
                } else {
                    $this->addMessage('delDefaultGroups', 'warning');
                    break;
                }
            }
        }

        $groupList = $groupMapper->getGroupList();
        $groupUsers = [];

        foreach ($groupList as $group) {
            $groupId = $group->getId();
            $groupUsers[$groupId] = $groupMapper->getUsersForGroup($groupId) ?? [];
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
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroup'), ['action' => 'index']);

        $groupId = $this->getRequest()->getParam('id');
        $groupMapper = new GroupMapper();
        $userMapper = new UserMapper();

        if ($groupId) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('editGroup'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('addGroup'), ['action' => 'treat']);
        }

        if ($groupId == 1 && !$this->getUser()->isAdmin()) {
            $this->redirect(['action' => 'index']);
        }

        if ($groupId && is_numeric($groupId) && $groupMapper->groupWithIdExists($groupId)) {
            $group = $groupMapper->getGroupById($groupId);
            $groupUsers = $groupMapper->getUsersForGroup($group->getId()) ?? [];
        } else {
            $group = new GroupModel();
            $groupUsers = [];
        }

        // The unassigned list is loaded on demand by the AJAX search
        // (searchUsersAction), so the page only needs the group's own members.
        // Fetched in one batched, field-limited query instead of one full-row
        // query per member in the view.
        $groupUserModels = $userMapper->getUserByIds($groupUsers);

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('groupUsersList', $groupUsers);
        $this->getView()->set('groupUsers', $groupUserModels);
        $this->getView()->set('group', $group);
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }

    /**
     * Returns up to 50 users whose name matches the given search as JSON.
     * AJAX endpoint for the user search on the treat page.
     * Admin-only by extending \Ilch\Controller\Admin (like all actions here).
     */
    public function searchUsersAction()
    {
        header('Content-Type: application/json; charset=utf-8');

        $userMapper = new UserMapper();

        $search = trim((string)$this->getRequest()->getParam('query', ''));
        if ($search !== '') {
            // Escape LIKE wildcards from user input.
            $search = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);
        }

        $pagination = new Pagination();
        $pagination->setRowsPerPage(50); // page 1 by default

        // Empty search => LIKE '%%' => first 50 users of the whole table.
        $users = $userMapper->getUserList(['name LIKE' => '%' . $search . '%'], $pagination, ['id', 'name']);

        $this->getView()->set('users', $users);
        $this->getView()->set('total', (int)$pagination->getRows());
    }

    /**
     * Saves the given group.
     */
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['group'])) {
            $validation = Validation::create($postData['group'], [
                // Normally an id is 1 or higher, but the model has a default of 0 instead of null.
                // Not changing this now to avoid possible breakage. id = 0 is the case for a new group.
                'id' => 'required|integer|min:0',
                'name' => 'required',
            ]);

            if ($validation->isValid()) {
                $groupData = $postData['group'];
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu') ?: '[]');
                $sortItems = is_array($sortItems) ? $sortItems : [];
                $groupMapper = new GroupMapper();
                $group = $groupMapper->loadFromArray($groupData);

                if ($group->getId() == 1 && !$this->getUser()->isAdmin()) {
                    $this->redirect(['action' => 'index']);
                }

                $groupId = $groupMapper->save($group);

                $groupUsers = $groupMapper->getUsersForGroup($groupId) ?? [];
                $userMapper = new UserMapper();
                foreach ($sortItems as $key => $user_Id) {
                    if (!\in_array($user_Id, $groupUsers)) {
                        $userMapper->addUserToGroup($user_Id, $groupId);
                        $groupUsers[] = $user_Id;
                    }
                }
                foreach ($groupUsers as $key => $user_Id) {
                    if (!\in_array($user_Id, $sortItems)) {
                        if ($groupId != 1 || (\count($groupUsers) > 1)) {
                            $userMapper->deleteUserToGroup($user_Id, $groupId);
                            unset($groupUsers[$key]);
                        } elseif (\count($groupUsers) <= 1) {
                            $this->addMessage('delLastAdminProhibited', 'warning');
                        }
                    }
                }

                if (!empty($groupId) && empty($groupData['id'])) {
                    $moduleMapper = new ModuleMapper();
                    $modules = $moduleMapper->getModules() ?? [];

                    $pageMapper = new PageMapper();
                    $pages = $pageMapper->getPageList() ?? [];

                    $articleMapper = new ArticleMapper();
                    $articles = $articleMapper->getArticles() ?? [];

                    $boxMapper = new BoxMapper();
                    $boxes = $boxMapper->getSelfBoxList('') ?? [];

                    $accessTypes = [
                        'module' => $modules,
                        'page' => $pages,
                        'article' => $articles,
                        'box' => $boxes,
                    ];

                    foreach ($accessTypes as $type => $TypeData) {
                        foreach ($TypeData as $TypeDataModel) {
                            if ($type === 'module') {
                                $value = $TypeDataModel->getKey();
                            } else {
                                $value = $TypeDataModel->getId();
                            }
                            $groupMapper->saveAccessData($groupId, $value, 1, $type);
                        }
                    }

                    $this->redirect()
                        ->withMessage('newGroupMsg')
                        ->to(['action' => 'treat', 'id' => $groupId]);
                } else {
                    $this->redirect()
                        ->withMessage('treatGroupMsg')
                        ->to(['action' => 'treat', 'id' => $groupId]);
                }
            }

            $this->redirect()
                ->withMessage('newOrTreatGroupInvalid', 'danger')
                ->to(['action' => 'index']);
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
            // Admingroup has always id "1" and is not allowed to be deleted.
            if ($groupId == 1) {
                $this->addMessage('delAdminGroup', 'warning');
            } elseif ($groupId == 2) {
                $this->addMessage('delUserGroup', 'warning');
            } elseif ($groupId == 3) {
                $this->addMessage('delGuestGroup', 'warning');
            } elseif ($groupMapper->delete($groupId)) {
                $this->addMessage('delGroupMsg');
            }
        }

        $this->redirect(['action' => 'index']);
    }

    /**
     * Shows a table with all groups.
     */
    public function accessAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroup'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuAccess'), ['action' => 'index']);

        $postData = $this->getRequest()->getPost();
        $groupMapper = new GroupMapper();
        $groups = $groupMapper->getGroupList();
        $this->getView()->set('activeGroupId', 0);
        $this->getView()->set('activeGroup', null);
        $this->getView()->set('activeaccessId', 0);
        $this->getView()->set('activeAccess', null);

        foreach ($groups as $key => $group) {
            if ($group->getId() == 1) {
                unset($groups[$key]);
            }
        }

        $this->getView()->set('groups', $groups);

        $groupId = 0;
        $accessId = 0;

        if (!empty($postData['groupId'])) {
            $groupId = (int)$postData['groupId'];
            $_SESSION['user']['accessGroup'] = $groupId;
            unset($_SESSION['user']['accessAccess']);
        } elseif (!empty($postData['accessId'])) {
            $accessId = $postData['accessId'];
            $_SESSION['user']['accessAccess'] = $accessId;
            unset($_SESSION['user']['accessGroup']);
        }

        if ($groupId) {
            $groupAccessList = $groupMapper->getGroupAccessList($groupId);
            $activeGroup = $groupMapper->getGroupById($groupId);
            $this->getView()->set('groupAccessList', $groupAccessList);
            $this->getView()->set('activeGroupId', $groupId);
            $this->getView()->set('activeGroup', $activeGroup);
        }

        if ($accessId) {
            list($Type, $Id) = explode('_', $accessId);
            $groupAccessList = $groupMapper->getAccessAccessList($Type, $Id);
            $this->getView()->set('accessAccessList', $groupAccessList);
            $this->getView()->set('activeaccessId', $accessId);
        }

        $moduleMapper = new ModuleMapper();
        $modules = $moduleMapper->getModules() ?? [];

        $pageMapper = new PageMapper();
        $pages = $pageMapper->getPageList() ?? [];

        $articleMapper = new ArticleMapper();
        $articles = $articleMapper->getArticles() ?? [];

        $boxMapper = new BoxMapper();
        $boxes = $boxMapper->getSelfBoxList('') ?? [];

        $accessTypes = [
            'module' => $modules,
            'page' => $pages,
            'article' => $articles,
            'box' => $boxes,
        ];

        $this->getView()->set('accessTypes', $accessTypes);
    }

    /**
     * Saves the group/access access rights.
     */
    public function saveAccessAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['groupAccess'], $postData['groupId'])) {
            if ((int)$postData['groupId'] !== 1) {
                $groupAccessData = $postData['groupAccess'];
                $groupMapper = new GroupMapper();

                foreach ($groupAccessData as $type => $groupsAccessTypeData) {
                    foreach ($groupsAccessTypeData as $value => $accessLevel) {
                        $groupMapper->saveAccessData($_SESSION['user']['accessGroup'], $value, $accessLevel, $type);
                    }
                }
            }

            $this->redirect(['action' => 'access']);
        } elseif (isset($postData['accessAccess'], $postData['accessId'])) {
            list($Type, $Id) = explode('_', $_SESSION['user']['accessAccess']);
            $accessAccessData = $postData['accessAccess'];
            $groupMapper = new GroupMapper();

            foreach ($accessAccessData as $groupId => $accessLevel) {
                $groupMapper->saveAccessData($groupId, $Id, $accessLevel, $Type);
            }

            $this->redirect(['action' => 'access']);
        }
    }
}
