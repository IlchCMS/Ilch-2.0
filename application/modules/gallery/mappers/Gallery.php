<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Mappers;

use Modules\Gallery\Models\GalleryItem as GalleryItem;

class Gallery extends \Ilch\Mapper
{
    /**
     * Gets all gallery items by parent item id.
     */
    public function getGalleryItemsByParent($galleryId, $itemId)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(array('gallery_id' => $galleryId, 'parent_id' => $itemId))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new GalleryItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemId);
            $itemModel->setGalleryId($galleryId);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets all gallery items by type.
     */
    public function getGalleryCatItem($type)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(array('type' => $type))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new GalleryItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setGalleryId($itemRow['gallery_id']);
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getGalleryById($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(array('id' => $id))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new GalleryItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setGalleryId($itemRows['gallery_id']);

        return $itemModel;
    }
    

    /**
     * Save one gallery item.
     *
     * @param  GalleryItem $galleryItem
     * @return integer
     */
    public function saveItem(GalleryItem $galleryItem)
    {
        $fields = array
        (
            'title' => $galleryItem->getTitle(),
            'gallery_id' => $galleryItem->getGalleryId(),
            'sort' => $galleryItem->getSort(),
            'parent_id' => $galleryItem->getParentId(),
            'type' => $galleryItem->getType(),
            'description' => $galleryItem->getDesc(),
        );

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('gallery_items')
            ->where(array('id' => $galleryItem->getId()))
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('gallery_items')
                ->values($fields)
                ->where(array('id' => $itemId))
                ->execute();
        } else {
            $itemId = $this->db()->insert('gallery_items')
                ->values($fields)
                ->execute();
        }

        return $itemId;
    }
 
    /**
     * Delete the given gallery item.
     *
     * @param  GalleryItem $galleryItem
     */
    public function deleteItem($galleryItem)
    {
        $this->db()->delete('gallery_items')
            ->where(array('id' => $galleryItem->getId()))
            ->execute();
    }

    /**
     * Gets all gallery items by gallery id.
     */
    public function getGalleryItems($galleryId)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(array('gallery_id' => $galleryId))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new GalleryItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setGalleryId($galleryId);
            $items[] = $itemModel;
        }

        return $items;
    }
}
