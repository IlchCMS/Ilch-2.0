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
     * @var string
     * @since 1.23.4
     */
    public $tablename = 'gallery_items';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.23.4
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return GalleryItem[]|null
     * @since 1.23.4
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['id', 'sort', 'parent_id', 'type', 'title', 'description'])
            ->from($this->tablename)
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new GalleryItem();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets all gallery items by parent item id.
     *
     * @param int $itemId
     * @return GalleryItem[]|null
     * @since 1.20.0 Removal of galleryId parameter.
     */
    public function getGalleryItemsByParent(int $itemId): ?array
    {
        return $this->getEntriesBy(['parent_id' => $itemId]);
    }

    /**
     * Gets all gallery items by type.
     *
     * @param int $type
     * @return GalleryItem[]
     */
    public function getGalleryCatItem(int $type): array
    {
        return $this->getEntriesBy(['type' => $type], ['sort' => 'ASC']);
    }

    /**
     * Get gallery by id.
     *
     * @param int $id
     * @return GalleryItem|null
     */
    public function getGalleryById(int $id): ?GalleryItem
    {
        $itemRows = $this->getEntriesBy(['id' => $id], ['sort' => 'ASC']);

        if ($itemRows) {
            return reset($itemRows);
        }

        return null;
    }

    /**
     * Save one gallery item.
     *
     * @param  GalleryItem $galleryItem
     * @return int
     */
    public function saveItem(GalleryItem $galleryItem): int
    {
        return $this->save($galleryItem);
    }

    /**
     * Inserts or updates galleryItem model.
     *
     * @param GalleryItem $galleryItem
     * @return int
     * @since 1.23.4
     */
    public function save(GalleryItem $galleryItem): int
    {
        $fields = $galleryItem->getArray(false);

        if ($galleryItem->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $galleryItem->getId()])
                ->execute();

                return $galleryItem->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Sort Gallery.
     *
     * @param int|GalleryItem $id
     * @param int $pos
     * @return bool
     */
    public function sort($id, int $pos, int $parent): bool
    {
        if (is_a($id, GalleryItem::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['sort' => $pos, 'parent_id' => $parent])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete the given gallery item.
     *
     * @param int|GalleryItem $id
     * @return bool
     */
    public function deleteItem($id): bool
    {
        if (is_a($id, GalleryItem::class)) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Gets all gallery items.
     *
     * @return GalleryItem[]|null
     */
    public function getGalleryItems(): ?array
    {
        return $this->getEntriesBy([], ['sort' => 'ASC']);
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.23.4
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
