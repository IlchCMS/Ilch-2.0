<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Mappers\Image as ImageMapper;
use Ilch\Validation;
use Modules\Gallery\Models\GalleryItem;

class Index extends \Ilch\Controller\Admin
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
                        'type' => 'required|numeric|integer',
                        'title' => 'required',
                    ]);
                    if (!$validation->isValid()) {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'index']);
                    }
                }

                $oldItems = $galleryMapper->getGalleryItems();

                // Delete no longer existing items.
                foreach($oldItems as $oldItem) {
                    if (!key_exists($oldItem->getId(), $items)) {
                        $galleryMapper->deleteItem($oldItem);
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
                        $galleryItem = new GalleryItem();

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $galleryItem->setId($item['id']);
                        }

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
                        $galleryItem = new GalleryItem();
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

        $galleryItems = $galleryMapper->getGalleryItemsByParent(0);
        $this->getView()->set('galleryItems', $galleryItems);
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }
}
