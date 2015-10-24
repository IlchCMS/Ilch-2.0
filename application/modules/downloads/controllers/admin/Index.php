<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Controllers\Admin\Base as BaseController;
use Modules\Downloads\Mappers\File as FileMapper;

class Index extends BaseController
{
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('downloads'), array('action' => 'index'));

        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = $this->getRequest()->getPost('items');
                $oldItems = $downloadsMapper->getDownloadsItems(1);

                /*
                 * Deletes old entries from database.
                 */
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        if (!isset($items[$oldItem->getId()])) {
                            $downloadsMapper->deleteItem($oldItem);
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
                        $downloadsItem = new \Modules\Downloads\Models\DownloadsItem;

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $downloadsItem->setId($item['id']);
                        }

                        $downloadsItem->setDownloadsId(1);
                        $downloadsItem->setType($item['type']);
                        $downloadsItem->setTitle($item['title']);
                        $downloadsItem->setDesc($item['desc']);
                        $newId = $downloadsMapper->saveItem($downloadsItem);

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
                        $downloadsItem = new \Modules\Downloads\Models\DownloadsItem();
                        $downloadsItem->setId($id);
                        $downloadsItem->setSort($sort);
                        $downloadsItem->setParentId($parent);
                        $downloadsMapper->saveItem($downloadsItem);
                        $sort += 10;
                    }
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(array('action' => 'index'));
        }

        $downloadsItems = $downloadsMapper->getDownloadsItemsByParent(1, 0);
        $this->getView()->set('downloadsItems', $downloadsItems);
        $this->getView()->set('downloadsMapper', $downloadsMapper);
        $this->getView()->set('fileMapper', $fileMapper);
    }
}
