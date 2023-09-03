<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\DownloadsItem;

class Index extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuDownloads',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('downloads'), ['action' => 'index']);

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
                    $sortArray = [];

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->id !== null) {
                            $sortArray[$sortItem->id] = (int)$sortItem->parent_id;
                        }
                    }

                    foreach ($items as $item) {
                        $downloadsItem = new DownloadsItem();

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
                        $downloadsItem = new DownloadsItem();
                        $downloadsItem->setId($id);
                        $downloadsItem->setSort($sort);
                        $downloadsItem->setParentId($parent);
                        $downloadsMapper->saveItem($downloadsItem);
                        $sort += 10;
                    }
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $downloadsItems = $downloadsMapper->getDownloadsItemsByParent(1, 0);
        $this->getView()->set('downloadsItems', $downloadsItems);
        $this->getView()->set('downloadsMapper', $downloadsMapper);
        $this->getView()->set('fileMapper', $fileMapper);
    }
}
