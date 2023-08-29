<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Mappers;

use Ilch\Mapper;
use Modules\Gallery\Models\GalleryItem;

/**
 * Gallery mapper.
 */
class Gallery extends Mapper
{
    /**
     * Gets all gallery items by parent item id.
     *
     * @param int $itemId
     * @return array|null
     * @since 1.20.0 Removal of galleryId parameter.
     */
    public function getGalleryItemsByParent(int $itemId): ?array
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
                ->where(['parent_id' => $itemId])
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
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Get gallery by id.
     *
     * @param int $id
     * @return GalleryItem|null
     */
    public function getGalleryById(int $id): ?GalleryItem
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
     * Gets all gallery items.
     *
     * @return array|null
     */
    public function getGalleryItems(): ?array
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('gallery_items')
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
            $items[] = $itemModel;
        }

        return $items;
    }
}
