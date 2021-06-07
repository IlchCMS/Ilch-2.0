<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\GalleryImage as GalleryImageModel;

class GalleryImage extends \Ilch\Mapper
{
    /**
     * Get image by id.
     *
     * @param int $id
     * @return GalleryImageModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getImageById($id)
    {
        $imageRow = $this->db()->select(['g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.cat' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $entryModel = new GalleryImageModel();
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageUrl($imageRow['url']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setCat($imageRow['cat']);
        $entryModel->setVisits($imageRow['visits']);

        return $entryModel;
    }

    /**
     * Get last image of gallery by category id.
     *
     * @param int $id
     * @return GalleryImageModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getLastImageByGalleryId($id)
    {
        $imageRow = $this->db()->select(['g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.cat' => $id])
            ->order(['g.id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $entryModel = new GalleryImageModel();
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageThumb($imageRow['url_thumb']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setVisits($imageRow['visits']);

        return $entryModel;
    }

    /**
     * Get count of images by category id.
     *
     * @param int $id
     * @return false|string|null
     */
    public function getCountImageById($id)
    {
        return $this->db()->select('COUNT(*)', 'users_gallery_imgs')
            ->where(['cat' => $id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Image entry.
     *
     * @param GalleryImageModel $model
     */
    public function save(GalleryImageModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('users_gallery_imgs')
                ->values(['image_id' => $model->getImageId(), 'cat' => $model->getCat()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('users_gallery_imgs')
                ->values(['user_id' => $model->getUserId(), 'image_id' => $model->getImageId(), 'cat' => $model->getCat()])
                ->execute();
        }
    }

    /**
     * Get images by gallery id.
     *
     * @param int $id
     * @param null|\Ilch\Pagination $pagination
     * @return array|null
     */
    public function getImageByGalleryId($id, $pagination = NULL)
    {
        $sql = $this->db()->select(['g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.cat' => $id])
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

        if (empty($imageArray)) {
            return null;
        }

        $entries = [];

        foreach ($imageArray as $entry) {
            $galleryImageModel = new GalleryImageModel();
            $galleryImageModel->setImageUrl($entry['url']);
            $galleryImageModel->setImageThumb($entry['url_thumb']);
            $galleryImageModel->setId($entry['imgid']);
            $galleryImageModel->setImageId($entry['id']);
            $galleryImageModel->setImageTitle($entry['image_title']);
            $galleryImageModel->setImageDesc($entry['image_description']);
            $galleryImageModel->setVisits($entry['visits']);
            $galleryImageModel->setCat($entry['cat']);
            $entries[] = $galleryImageModel;
        }

        return $entries;
    }

    /**
     * Delete gallery image by id.
     *
     * @param int $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteById($id)
    {
        return $this->db()->delete('users_gallery_imgs')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates visits.
     *
     * @param GalleryImageModel $model
     */
    public function saveVisits(GalleryImageModel $model)
    {
        if ($model->getVisits()) {
            $this->db()->update('users_gallery_imgs')
                ->values(['visits' => $model->getVisits()])
                ->where(['image_id' => $model->getImageId()])
                ->execute();
        }
    }

    /**
     * Updates image meta.
     *
     * @param GalleryImageModel $model
     */
    public function saveImageTreat(GalleryImageModel $model)
    {
        $this->db()->update('users_gallery_imgs')
            ->values(['image_title' => $model->getImageTitle(), 'image_description' => $model->getImageDesc()])
            ->where(['id' => $model->getId()])
            ->execute();
    }
}
