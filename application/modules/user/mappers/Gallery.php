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
    public function getGalleryItemsByParent($userId, $itemId): ?array
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
            $itemModel->setSort($itemRow['sort']);
            $itemModel->setParentId($itemId);
            $itemModel->setUserId($userId);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets all gallery items by type.
     */
    public function getGalleryCatItem($type): ?array
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
            $itemModel->setSort($itemRow['sort']);
            $itemModel->setParentId($itemRow['parent_id']);
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getGalleryById($id): ?GalleryItem
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
        $itemModel->setSort($itemRows['sort']);
        $itemModel->setParentId($itemRows['parent_id']);

        return $itemModel;
    }

    /**
     * Gets all gallery items by user id.
     *
     * @param int $userId
     * @return null|GalleryItem[];
     */
    public function getGalleryItems(int $userId): ?array
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
            $itemModel->setSort($itemRow['sort']);
            $itemModel->setParentId($itemRow['parent_id']);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets the number of root gallery items (parent_id = 0) of a user.
     */
    public function getCountGalleryByUser(int $userId): int
    {
        return (int)$this->db()->select('COUNT(*)')
            ->from('users_gallery_items')
            ->where(['user_id' => $userId, 'parent_id' => 0])
            ->execute()
            ->fetchCell();
    }

    /**
     * Save one gallery item.
     *
     * @param  GalleryItem $galleryItem
     * @return int
     * @throws \InvalidArgumentException if a new item is missing a required field
     */
    public function saveItem(GalleryItem $galleryItem): int
    {
        $fields = [
            'user_id' => $galleryItem->getUserId(),
            'title' => $galleryItem->getTitle(),
            'sort' => $galleryItem->getSort(),
            'parent_id' => $galleryItem->getParentId(),
            'type' => $galleryItem->getType(),
            'description' => $galleryItem->getDesc()
        ];

        $itemId = (int)$this->db()->select('id')
            ->from('users_gallery_items')
            ->where(['id' => $galleryItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            // Only update the fields that were actually set,
            // the rest keeps its old database values.
            $updateFields = array_filter($fields, function ($value) {
                return $value !== null;
            });

            if ($updateFields !== []) {
                $this->db()->update('users_gallery_items')
                    ->values($updateFields)
                    ->where(['id' => $itemId])
                    ->execute();
            }
        } else {
            // `user_id`, `title` and `type` are NOT NULL columns without a
            // default value, so a new item must provide them.
            foreach (['user_id', 'title', 'type'] as $requiredField) {
                if ($fields[$requiredField] === null) {
                    throw new \InvalidArgumentException(
                        'Cannot insert a gallery item without its "' . $requiredField . '".'
                    );
                }
            }

            // Supply safe values for the remaining columns on insert.
            $fields['description'] = $fields['description'] ?? '';
            $fields['sort'] = $fields['sort'] ?? 0;
            $fields['parent_id'] = $fields['parent_id'] ?? 0;

            $itemId = $this->db()->insert('users_gallery_items')
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
    public function deleteItem(GalleryItem $galleryItem): void
    {
        $this->db()->delete('users_gallery_items')
            ->where(['id' => $galleryItem->getId()])
            ->execute();
    }
}
