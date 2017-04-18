<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Mappers\Image as ImageMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuGallery',
            $items
        );
    }

    public function indexAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = $this->getRequest()->getPost('items');

                foreach ($items as $item) {
                    $validation = Validation::create($item, [
                        'type' => 'required|numeric',
                        'title' => 'required',
                    ]);
                    if (!$validation->isValid()) {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'index']);
                    }
                }

                $oldItems = $galleryMapper->getGalleryItems(1);

                /*
                 * Deletes old entries from database.
                 */
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        if (!isset($items[$oldItem->getId()])) {
                            $galleryMapper->deleteItem($oldItem);
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
                        $galleryItem = new \Modules\Gallery\Models\GalleryItem;

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $galleryItem->setId($item['id']);
                        }

                        $galleryItem->setGalleryId(1);
                        $galleryItem->setType($item['type']);
                        $galleryItem->setTitle($item['title']);
                        $galleryItem->setDesc($item['desc']);
                        $newId = $galleryMapper->saveItem($galleryItem);

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
                        $galleryItem = new \Modules\Gallery\Models\GalleryItem();
                        $galleryItem->setId($id);
                        $galleryItem->setSort($sort);
                        $galleryItem->setParentId($parent);
                        $galleryMapper->saveItem($galleryItem);
                        $sort += 10;
                    }
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $galleryItems = $galleryMapper->getGalleryItemsByParent(1, 0);
        $this->getView()->set('galleryItems', $galleryItems);
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }
}
