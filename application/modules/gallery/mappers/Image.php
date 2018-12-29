<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Mappers;

use Modules\Gallery\Models\Image as ImageModel;

class Image extends \Ilch\Mapper
{
    /**
     * @param $id
     * @return ImageModel
     * @throws \Ilch\Database\Exception
     */
    public function getImageById($id)
    {
        $sql = 'SELECT g.image_id,g.cat,g.id as imgid,g.visits,g.image_title,g.image_description, m.url, m.id, m.url_thumb
                           FROM `[prefix]_gallery_imgs` AS g
                           LEFT JOIN `[prefix]_media` m ON g.image_id = m.id

                           WHERE g.id = '.$id;
        $imageRow = $this->db()->queryRow($sql);
        $entryModel = new ImageModel();
        $entryModel->setId($imageRow['imgid']);
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageUrl($imageRow['url']);
        $entryModel->setImageThumb($imageRow['url_thumb']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setCat($imageRow['cat']);
        $entryModel->setVisits($imageRow['visits']);

        return $entryModel;
    }

    /**
     * @param $id
     * @return ImageModel
     * @throws \Ilch\Database\Exception
     */
    public function getLastImageByGalleryId($id)
    {
        $sql = 'SELECT g.image_id,g.cat,g.id as imgid,g.visits,g.image_title,g.image_description, m.url, m.id, m.url_thumb
                           FROM `[prefix]_gallery_imgs` AS g
                           LEFT JOIN `[prefix]_media` m ON g.image_id = m.id

                           WHERE g.cat = '.$id.' ORDER by g.id DESC LIMIT 1';
        $imageRow = $this->db()->queryRow($sql);
        $entryModel = new ImageModel();
        $entryModel->setImageId($imageRow['image_id']);
        $entryModel->setImageThumb($imageRow['url_thumb']);
        $entryModel->setImageTitle($imageRow['image_title']);
        $entryModel->setImageDesc($imageRow['image_description']);
        $entryModel->setVisits($imageRow['visits']);

        return $entryModel;
    }

    /**
     * @param $id
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getCountImageById($id)
    {
        $sql = 'SELECT *
                FROM `[prefix]_gallery_imgs`
                
                WHERE cat = '.$id;
        $count = $this->db()->queryArray($sql);

        return $count;
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
                ->values(['image_id' => $model->getImageId(),'cat' => $model->getCat()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('gallery_imgs')
                ->values(['image_id' => $model->getImageId(),'cat' => $model->getCat()])
                ->execute();
        }
    }

    /**
     * @param $id
     * @param null $pagination
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getImageByGalleryId($id, $pagination = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS g.image_id,g.cat,g.id as imgid,g.image_title,g.image_description,g.visits, m.url, m.id, m.url_thumb
                           FROM `[prefix]_gallery_imgs` AS g
                           LEFT JOIN `[prefix]_media` m ON g.image_id = m.id

                           WHERE g.cat = '.$id.' ORDER BY g.id DESC
                           LIMIT '.implode(',',$pagination->getLimit());

        $imageArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        $entry = [];

        foreach ($imageArray as $entries) {
            $entryModel = new ImageModel();
            $entryModel->setImageUrl($entries['url']);
            $entryModel->setImageThumb($entries['url_thumb']);
            $entryModel->setId($entries['imgid']);
            $entryModel->setImageTitle($entries['image_title']);
            $entryModel->setImageDesc($entries['image_description']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setCat($entries['cat']);
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
    public function getListOfValidIds($where = [])
    {
        return $this->db()->select('id')
            ->from('gallery_imgs')
            ->where($where, 'or')
            ->execute()
            ->fetchList();
    }

    /**
     * @param $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteById($id)
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
                ->values(['image_title' => $model->getImageTitle(),'image_description' => $model->getImageDesc()])
                ->where(['id' => $model->getId()])
                ->execute();
    }
}
