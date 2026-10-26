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
     */
    public function getImageById(int $id): ?GalleryImageModel
    {
        $imageRow = $this->db()->select(['g.user_id', 'g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.is_public', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where(['g.id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $entryModel = new GalleryImageModel();
        $entryModel->setId($imageRow['imgid']);
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageUrl($imageRow['url']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setCat($imageRow['cat']);
        $entryModel->setVisits($imageRow['visits']);
        $entryModel->setUserId((int)$imageRow['user_id']);
        $entryModel->setIsPublic((int)$imageRow['is_public']);

        return $entryModel;
    }

    /**
     * Get last image of gallery by category id.
     *
     * @param int $id
     * @return GalleryImageModel|null
     */
    public function getLastImageByGalleryId(int $id, bool $onlyPublic = false): ?GalleryImageModel
    {
        $where = ['g.cat' => $id];

        if ($onlyPublic) {
            $where['g.is_public'] = 1;
        }

        $imageRow = $this->db()->select(['g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'g.is_public', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where($where)
            ->order(['g.id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $entryModel = new GalleryImageModel();
        $entryModel->setId($imageRow['imgid']);
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageThumb($imageRow['url_thumb']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setCat($imageRow['cat']);
        $entryModel->setVisits($imageRow['visits']);
        $entryModel->setIsPublic((int)$imageRow['is_public']);

        return $entryModel;
    }

    /**
     * Get count of images by category id.
     *
     * @param int $id
     * @return false|string|null
     */
    public function getCountImageById(int $id, bool $onlyPublic = false): bool|string|null
    {
        $where = ['cat' => $id];

        if ($onlyPublic) {
            $where['is_public'] = 1;
        }

        return $this->db()->select('COUNT(*)', 'users_gallery_imgs')
            ->where($where)
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Image entry.
     *
     * Note: the update branch is a silent no-op for unknown ids, and the insert
     * branch does not persist image_title/image_description (column defaults
     * apply; use saveImageTreat() for those). The insert branch is also a no-op
     * when (image_id, cat) already exists, so re-adding the same media to the
     * same gallery cannot create duplicate rows.
     *
     * @param GalleryImageModel $model
     */
    public function save(GalleryImageModel $model): void
    {
        if ($model->getId()) {
            $this->db()->update('users_gallery_imgs')
                ->values(['image_id' => $model->getImageId(), 'cat' => $model->getCat()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $existing = (int)$this->db()->select('COUNT(*)')
                ->from('users_gallery_imgs')
                ->where(['image_id' => $model->getImageId(), 'cat' => $model->getCat()])
                ->execute()
                ->fetchCell();

            if ($existing > 0) {
                return;
            }

            $this->db()->insert('users_gallery_imgs')
                ->values(['user_id' => $model->getUserId(), 'image_id' => $model->getImageId(), 'cat' => $model->getCat(), 'is_public' => $model->getIsPublic() ?? 1])
                ->execute();
        }
    }

    /**
     * Get images by gallery id.
     *
     * @param int $id
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getImageByGalleryId(int $id, ?\Ilch\Pagination $pagination = null, bool $onlyPublic = false): ?array
    {
        $where = ['g.cat' => $id];

        if ($onlyPublic) {
            $where['g.is_public'] = 1;
        }

        $select = $this->db()->select(['g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.visits', 'g.is_public', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where($where)
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
            $galleryImageModel->setImageId($entry['image_id']);
            $galleryImageModel->setImageTitle($entry['image_title']);
            $galleryImageModel->setImageDesc($entry['image_description']);
            $galleryImageModel->setVisits($entry['visits']);
            $galleryImageModel->setCat($entry['cat']);
            $galleryImageModel->setIsPublic((int)$entry['is_public']);
            $entries[] = $galleryImageModel;
        }

        return $entries;
    }

    /**
     * Gets a gallery image by its media id.
     *
     * @param int $mediaId
     * @param int|null $userId Optional user id, restricts the lookup to that user.
     * @return GalleryImageModel|null
     */
    public function getImageByMediaId(int $mediaId, ?int $userId = null): ?GalleryImageModel
    {
        $where = ['g.image_id' => $mediaId];

        if ($userId !== null) {
            $where['g.user_id'] = $userId;
        }

        $imageRow = $this->db()->select(['g.user_id', 'g.image_id', 'g.cat', 'imgid' => 'g.id', 'g.image_title', 'g.image_description', 'g.is_public', 'g.visits', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'users_gallery_imgs'])
            ->join(['m' => 'users_media'], 'g.image_id = m.id', 'LEFT')
            ->where($where)
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($imageRow)) {
            return null;
        }

        $entryModel = new GalleryImageModel();
        $entryModel->setId($imageRow['imgid']);
        $entryModel->setUserId((int)$imageRow['user_id']);
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageUrl($imageRow['url']);
        $entryModel->setImageThumb($imageRow['url_thumb']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setIsPublic((int)$imageRow['is_public']);
        $entryModel->setCat($imageRow['cat']);
        $entryModel->setVisits($imageRow['visits']);

        return $entryModel;
    }

    /**
     * Deletes all gallery image entries that reference the given media
     * entry and belong to the given user.
     *
     * @param int $mediaId
     * @param int $userId
     */
    public function deleteByImageId(int $mediaId, int $userId): void
    {
        $this->db()->delete('users_gallery_imgs')
            ->where(['image_id' => $mediaId, 'user_id' => $userId])
            ->execute();
    }

    /**
     * Delete gallery image by id.
     *
     * @param int $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteById(int $id): \Ilch\Database\Mysql\Result|int
    {
        return $this->db()->delete('users_gallery_imgs')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates visits.
     *
     * Note: the update is done by image_id (the media entry), so all gallery
     * images referencing the same media entry receive the new visit count.
     * A value of 0 is stored as well, so the counter can be reset.
     *
     * @param GalleryImageModel $model
     */
    public function saveVisits(GalleryImageModel $model): void
    {
        $this->db()->update('users_gallery_imgs')
            ->values(['visits' => $model->getVisits()])
            ->where(['image_id' => $model->getImageId()])
            ->execute();
    }

    /**
     * Updates image meta.
     *
     * @param GalleryImageModel $model
     */
    public function saveImageTreat(GalleryImageModel $model): void
    {
        $this->db()->update('users_gallery_imgs')
            ->values(['image_title' => $model->getImageTitle(), 'image_description' => $model->getImageDesc(), 'is_public' => $model->getIsPublic() ?? 1])
            ->where(['id' => $model->getId()])
            ->execute();
    }
}
