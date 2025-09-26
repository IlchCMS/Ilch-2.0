<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Ilch\Pagination;
use Modules\Gallery\Models\Image as ImageModel;

/**
 * Image mapper
 */
class Image extends Mapper
{
    /**
     * @var string
     * @since 1.23.4
     */
    public $tablename = 'gallery_imgs';

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
     * @return ImageModel[]|null
     * @since 1.23.4
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['g.id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['g.image_id', 'g.gallery_id', 'imgid' => 'g.id', 'g.visits', 'g.image_title', 'g.image_description'])
            ->from(['g' => $this->tablename])
            ->join(['m' => 'media'], 'g.image_id = m.id', 'LEFT', ['m.url', 'm.id', 'm.url_thumb'])
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
            $entryModel = new ImageModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * @param int $id
     * @return ImageModel|null
     */
    public function getImageById(int $id): ?ImageModel
    {
        $imageRow = $this->getEntriesBy(['g.id' => $id]);

        if ($imageRow) {
            return reset($imageRow);
        }
        return null;
    }

    /**
     * Get last image by gallery id.
     *
     * @param int $id
     * @return ImageModel|null
     */
    public function getLastImageByGalleryId(int $id): ?ImageModel
    {
        $imageRow = $this->getEntriesBy(['g.gallery_id' => $id], ['g.id' => 'DESC']);

        if ($imageRow) {
            return reset($imageRow);
        }
        return null;
    }

    /**
     * Get count of images by id of category.
     *
     * @param int $id
     * @return int
     */
    public function getCountImageById(int $id): int
    {
        return (int)$this->db()->select('COUNT(*)', $this->tablename)
            ->where(['gallery_id' => $id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Image entry.
     *
     * @param ImageModel $model
     * @return int
     */
    public function save(ImageModel $model): int
    {
        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values(['image_id' => $model->getImageId(), 'gallery_id' => $model->getGalleryId()])
                ->where(['id' => $model->getId()])
                ->execute();
            return $model->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values(['image_id' => $model->getImageId(), 'gallery_id' => $model->getGalleryId()])
                ->execute();
        }
    }

    /**
     * Get images by gallery id.
     *
     * @param int $id
     * @param Pagination|null $pagination
     * @return ImageModel[]
     */
    public function getImageByGalleryId(int $id, ?Pagination $pagination = null): array
    {
        $imageArray = $this->getEntriesBy(['g.gallery_id' => $id], ['g.id' => 'DESC'], $pagination);

        if (!$imageArray) {
            return [];
        }
        return $imageArray;
    }

    /**
     * Get a list of valid image ids.
     *
     * @param array $where
     * @return string[]
     */
    public function getListOfValidIds(array $where = []): array
    {
        return $this->db()->select('id')
            ->from($this->tablename)
            ->where($where, 'or')
            ->execute()
            ->fetchList();
    }

    /**
     * Delete entries for images by id.
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates visits.
     *
     * @param ImageModel $model
     * @return bool
     */
    public function saveVisits(ImageModel $model): bool
    {
        if ($model->getVisits()) {
            return $this->db()->update($this->tablename)
                ->values(['visits' => $model->getVisits()])
                ->where(['image_id' => $model->getImageId()])
                ->execute();
        }
        return false;
    }

    /**
     * Updates image meta.
     *
     * @param ImageModel $model
     * @return bool
     */
    public function saveImageTreat(ImageModel $model): bool
    {
        return $this->db()->update($this->tablename)
            ->values(['image_title' => $model->getImageTitle(), 'image_description' => $model->getImageDesc()])
            ->where(['id' => $model->getId()])
            ->execute();
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
