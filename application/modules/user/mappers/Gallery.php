<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\GalleryItem;

class Gallery extends \Ilch\Mapper
{
    /**
     * Gets all gallery items by parent item id.
     */
    public function getGalleryItemsByParent($userId, $itemId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('users_gallery_items')
                ->where(['user_id' => $userId, 'parent_id' => $itemId])
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
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('users_gallery_items')
                ->where(['type' => $type])
                ->order(['sort' => 'ASC'])
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
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getGalleryById($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('users_gallery_items')
                ->where(['id' => $id])
                ->order(['sort' => 'ASC'])
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

        return $itemModel;
    }

    /**
     * Gets all gallery items by user id.
     *
     * @param int $userId
     * @return null|GalleryItem[];
     */
    public function getGalleryItems($userId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('users_gallery_items')
                ->where(['user_id' => $userId])
                ->order(['sort' => 'ASC'])
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
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getCountGalleryByUser($id)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_users_gallery_items`
                WHERE `user_id` = '.$id.' AND `parent_id` = 0';

        return $this->db()->queryCell($sql);
    }

    /**
     * Save one gallery item.
     *
     * @param  GalleryItem $galleryItem
     * @return int
     */
    public function saveItem(GalleryItem $galleryItem)
    {
        $fields = [
            'user_id' => $galleryItem->getUserId(),
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
            ->from('users_gallery_items')
            ->where(['id' => $galleryItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('users_gallery_items')
                ->values($fields)
                ->where(['id' => $itemId])
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
            ->where(['id' => $galleryItem->getId()])
            ->execute();
    }
}
