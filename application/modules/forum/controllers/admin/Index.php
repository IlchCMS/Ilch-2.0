<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => true,
                'icon' => 'fa fa-th',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuReports',
                'active' => false,
                'icon' => 'fas fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
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

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = (!empty($this->getRequest()->getPost('items'))) ? $this->getRequest()->getPost('items') : [];

                foreach ($items as $item) {
                    $validation = Validation::create($item, [
                        'type' => 'required|numeric|integer',
                        'title' => 'required',
                        'readAccess' => 'min:0',
                        'replayAccess' => 'min:0',
                    ]);
                    if (!$validation->isValid()) {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'index']);
                    }
                }

                $oldItems = $forumMapper->getForumItems();

                /*
                 * Deletes old entries from database.
                 */
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        if (!isset($items[$oldItem->getId()])) {
                            $forumMapper->deleteItem($oldItem);
                        }
                    }
                }

                if ($items) {
                    $sortArray = [];

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->item_id !== null) {
                            $sortArray[$sortItem->item_id] = (int)$sortItem->parent_id;
                        }
                    }

                    foreach ($items as $item) {
                        $forumItem = new \Modules\Forum\Models\ForumItem;

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $forumItem->setId($item['id']);
                        }

                        $forumItem->setType($item['type']);
                        $forumItem->setTitle($item['title']);
                        $forumItem->setDesc($item['desc']);
                        // Don't try to store these values for a categorie. This avoids storing "undefined" from JS in the database.
                        if ($item['type'] != 0) {
                            $forumItem->setPrefix($item['prefix']);
                            $forumItem->setReadAccess($item['readAccess']);
                            $forumItem->setReplayAccess($item['replayAccess']);
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
                        $forumItem = new \Modules\Forum\Models\ForumItem();
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

        $forumItems = $forumMapper->getForumItemsByParent(0);
        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);

        $userGroupList = $userGroupMapper->getGroupList();
        $this->getView()->set('userGroupList', $userGroupList);
    }
}
