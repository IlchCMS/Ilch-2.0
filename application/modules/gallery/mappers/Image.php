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
     * @param int $id
     * @return ImageModel|null
     */
    public function getImageById(int $id): ?ImageModel
    {
        $imageRow = $this->db()->select(['g.image_id', 'g.gallery_id', 'imgid' => 'g.id', 'g.visits', 'g.image_title', 'g.image_description', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'gallery_imgs'])
            ->join(['m' => 'media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $imageModel = new ImageModel();
        $imageModel->setId($imageRow['imgid']);
        $imageModel->setImageId($imageRow['image_id']);
        $imageModel->setImageUrl((string)$imageRow['url']);
        $imageModel->setImageThumb((string)$imageRow['url_thumb']);
        $imageModel->setImageTitle($imageRow['image_title']);
        $imageModel->setImageDesc($imageRow['image_description']);
        $imageModel->setGalleryId($imageRow['gallery_id']);
        $imageModel->setVisits($imageRow['visits']);

        return $imageModel;
    }

    /**
     * Get last image by gallery id.
     *
     * @param int $id
     * @return ImageModel|null
     */
    public function getLastImageByGalleryId(int $id): ?ImageModel
    {
        $imageRow = $this->db()->select(['g.image_id', 'g.gallery_id', 'g.visits', 'g.image_title', 'g.image_description', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'gallery_imgs'])
            ->join(['m' => 'media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.gallery_id' => $id])
            ->order(['g.id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $imageModel = new ImageModel();
        $imageModel->setImageId($imageRow['image_id']);
        $imageModel->setImageThumb((string)$imageRow['url_thumb']);
        $imageModel->setImageTitle($imageRow['image_title']);
        $imageModel->setImageDesc($imageRow['image_description']);
        $imageModel->setVisits($imageRow['visits']);

        return $imageModel;
    }

    /**
     * Get count of images by id of category.
     *
     * @param int $id
     * @return int
     */
    public function getCountImageById(int $id): int
    {
        return (int)$this->db()->select('COUNT(*)', 'gallery_imgs')
            ->where(['gallery_id' => $id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Image entry.
     *
     * @param ImageModel $model
     */
    public function save(ImageModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('gallery_imgs')
                ->values(['image_id' => $model->getImageId(), 'gallery_id' => $model->getGalleryId()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('gallery_imgs')
                ->values(['image_id' => $model->getImageId(), 'gallery_id' => $model->getGalleryId()])
                ->execute();
        }
    }

    /**
     * Get images by gallery id.
     *
     * @param int $id
     * @param Pagination|null $pagination
     * @return array
     */
    public function getImageByGalleryId(int $id, ?Pagination $pagination = null): array
    {
        $select = $this->db()->select(['g.image_id', 'g.gallery_id', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'gallery_imgs'])
            ->join(['m' => 'media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.gallery_id' => $id])
            ->order(['g.id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $imageArray = $result->fetchRows();
        $entry = [];

        foreach ($imageArray as $entries) {
            $entryModel = new ImageModel();
            $entryModel->setImageUrl((string)$entries['url']);
            $entryModel->setImageThumb((string)$entries['url_thumb']);
            $entryModel->setId($entries['imgid']);
            $entryModel->setImageTitle($entries['image_title']);
            $entryModel->setImageDesc($entries['image_description']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setGalleryId($entries['gallery_id']);
            $entry[] = $entryModel;
        }

        return $entry;
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
            ->from('gallery_imgs')
            ->where($where, 'or')
            ->execute()
            ->fetchList();
    }

    /**
     * Delete entries for images by id.
     *
     * @param int $id
     * @return Result|int
     */
    public function deleteById(int $id)
    {
        return $this->db()->delete('gallery_imgs')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates visits.
     *
     * @param ImageModel $model
     */
    public function saveVisits(ImageModel $model)
    {
        if ($model->getVisits()) {
            $this->db()->update('gallery_imgs')
                ->values(['visits' => $model->getVisits()])
                ->where(['image_id' => $model->getImageId()])
                ->execute();
        }
    }

    /**
     * Updates image meta.
     *
     * @param ImageModel $model
     */
    public function saveImageTreat(ImageModel $model)
    {
        $this->db()->update('gallery_imgs')
            ->values(['image_title' => $model->getImageTitle(), 'image_description' => $model->getImageDesc()])
            ->where(['id' => $model->getId()])
            ->execute();
    }
}
