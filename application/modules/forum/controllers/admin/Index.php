<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Models\ForumItem;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Index extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => true,
                'icon' => 'fa-solid fa-table-cells',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuReports',
                'active' => false,
                'icon' => 'fa-solid fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'forum',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['action' => 'index']);

        $forumMapper = new ForumMapper();
        $userGroupMapper = new UserGroupMapper();

        // Saves the item tree to database.
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = (!empty($this->getRequest()->getPost('items'))) ? $this->getRequest()->getPost('items') : [];

                foreach ($items as $item) {
                    $validation = Validation::create($item, [
                        'type' => 'required|numeric|integer',
                        'title' => 'required',
                        'readAccess' => 'min:0',
                        'replyAccess' => 'min:0',
                        'createAccess' => 'min:0',
                    ]);
                    if (!$validation->isValid()) {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'index']);
                    }
                }

                $oldItems = $forumMapper->getForumItemsIds();

                // Deletes old entries from database.
                if (!empty($oldItems)) {
                    $itemsToDelete = array_diff($oldItems, array_keys($items));
                    if (!empty($itemsToDelete)) {
                        $forumMapper->deleteItems($itemsToDelete);
                    }
                }

                if ($items) {
                    $sortArray = [];

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->id !== null) {
                            $sortArray[$sortItem->id] = (int)$sortItem->parent_id;
                        }
                    }

                    foreach ($items as $item) {
                        $forumItem = new ForumItem();

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $forumItem->setId($item['id']);
                        }

                        $forumItem->setType($item['type']);
                        $forumItem->setTitle($item['title']);
                        $forumItem->setDesc($item['desc']);
                        // Don't try to store these values for a category. This avoids storing "undefined" from JS in the database.
                        if ($item['type'] != 0) {
                            $forumItem->setPrefix($item['prefix']);
                            $forumItem->setReadAccess($item['readAccess']);
                            $forumItem->setReplyAccess($item['replyAccess']);
                            $forumItem->setCreateAccess($item['createAccess']);
                        }

                        $newId = $forumMapper->saveItem($forumItem);

                        if (isset($tmpId)) {
                            foreach ($sortArray as $id => $parentId) {
                                if ($id == $tmpId) {
                                    unset($sortArray[$id]);
                                    $sortArray[$newId] = $parentId;
                                }

                                if ($parentId == $tmpId) {
                                    $sortArray[$id] = $newId;
                                }
                            }
                        }
                    }

                    $sort = 0;

                    foreach ($sortArray as $id => $parent) {
                        $forumItem = new ForumItem();
                        $forumItem->setId($id);
                        $forumItem->setSort($sort);
                        $forumItem->setParentId($parent);
                        $forumMapper->saveItem($forumItem);
                        $sort += 10;
                    }
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('forumItems', $forumMapper->getForumItemsAdmincenterByParentIds([0]));
        $this->getView()->set('userGroupList', $userGroupMapper->getGroupList());
    }
}
