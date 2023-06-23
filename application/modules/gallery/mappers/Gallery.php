<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Mappers;

use Modules\Gallery\Models\GalleryItem;

/**
 * Gallery mapper.
 */
class Gallery extends \Ilch\Mapper
{
    /**
     * Gets all gallery items by parent item id.
     *
     * @param $galleryId
     * @param $itemId
     * @return array|null
     */
    public function getGalleryItemsByParent($galleryId, $itemId): ?array
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(['gallery_id' => $galleryId, 'parent_id' => $itemId])
                ->order(['sort' => 'ASC'])
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
     *
     * @param int $type
     * @return array|GalleryItem[]
     */
    public function getGalleryCatItem(int $type): array
    {
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(['type' => $type])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return [];
        }

        $items = [];
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

    /**
     * Get gallery by id.
     *
     * @param $id
     * @return GalleryItem|null
     */
    public function getGalleryById($id): ?GalleryItem
    {
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(['id' => $id])
                ->order(['sort' => 'ASC'])
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
     * @return int
     */
    public function saveItem(GalleryItem $galleryItem): int
    {
        $fields = [
            'title' => $galleryItem->getTitle(),
            'gallery_id' => $galleryItem->getGalleryId(),
            'sort' => $galleryItem->getSort(),
            'parent_id' => $galleryItem->getParentId(),
            'type' => $galleryItem->getType(),
            'description' => $galleryItem->getDesc()
        ];

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('gallery_items')
            ->where(['id' => $galleryItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('gallery_items')
                ->values($fields)
                ->where(['id' => $itemId])
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
     * @param GalleryItem $galleryItem
     */
    public function deleteItem(GalleryItem $galleryItem)
    {
        $this->db()->delete('gallery_items')
            ->where(['id' => $galleryItem->getId()])
            ->execute();
    }

    /**
     * Gets all gallery items by gallery id.
     *
     * @param $galleryId
     * @return array|null
     */
    public function getGalleryItems($galleryId): ?array
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(['gallery_id' => $galleryId])
                ->order(['sort' => 'ASC'])
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
