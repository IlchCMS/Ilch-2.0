<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Controllers\Admin\Base as BaseController;
use Modules\User\Mappers\Group as UserGroupMapper;

defined('ACCESS') or die('no direct access');

class Index extends BaseController 
{
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), array('action' => 'index'));

        $forumMapper = new ForumMapper();
        $userGroupMapper = new UserGroupMapper();

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = $this->getRequest()->getPost('items');
                $oldItems = $forumMapper->getForumItems(1);

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
                    $sortArray = array();

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

                        $forumItem->setForumId(1);
                        $forumItem->setType($item['type']);
                        $forumItem->setTitle($item['title']);
                        $forumItem->setDesc($item['desc']);
                        $forumItem->setReadAccess($item['readAccess']);
                        $forumItem->setReplayAccess($item['replayAccess']);
                        $forumItem->setCreateAccess($item['createAccess']);
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
            $this->redirect(array('action' => 'index'));
        }

        $forumItems = $forumMapper->getForumItemsByParent(1, 0);
        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);

        $userGroupList = $userGroupMapper->getGroupList();
        $this->getView()->set('userGroupList', $userGroupList);
    }
}
