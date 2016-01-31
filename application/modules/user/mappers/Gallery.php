<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\GalleryItem as GalleryItem;

class Gallery extends \Ilch\Mapper
{
    /**
     * Gets all gallery items by parent item id.
     */
    public function getGalleryItemsByParent($userId, $galleryId, $itemId)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('users_gallery_items')
                ->where(array('user_id' => $userId, 'gallery_id' => $galleryId, 'parent_id' => $itemId))
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
            $itemModel->setUserId($userId);
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
                ->from('users_gallery_items')
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
            $itemModel->setUserId($itemRow['user_id']);
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
                ->from('users_gallery_items')
                ->where(array('id' => $id))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new GalleryItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setUserId($itemRows['user_id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setGalleryId($itemRows['gallery_id']);

        return $itemModel;
    }

    public function getCountGalleryByUser($id)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_users_gallery_items`
                WHERE `user_id` = '.$id.' AND `parent_id` = 0';

        $gallery = $this->db()->queryCell($sql);

        return $gallery;
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
            'user_id' => $galleryItem->getUserId(),
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
            ->from('users_gallery_items')
            ->where(array('id' => $galleryItem->getId()))
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('users_gallery_items')
                ->values($fields)
                ->where(array('id' => $itemId))
                ->execute();
        } else {
            $itemId = $this->db()->insert('users_gallery_items')
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
        $this->db()->delete('users_gallery_items')
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
                ->from('users_gallery_items')
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
            $itemModel->setUserId($itemRow['user_id']);
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
