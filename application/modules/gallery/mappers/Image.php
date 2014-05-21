<?php
/**
 * Holds Image Mapper.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Gallery\Mappers;

use Gallery\Models\Image as ImageModel;

defined('ACCESS') or die('no direct access');

class Image extends \Ilch\Mapper 
{
    /**
     * Gets the image.
     *
     * @param array $where
     * @return ImageModel[]|array
     */
    public function getImage($id)
    {
        $sql = 'SELECT g.image_id,g.cat,g.id as imgid, m.url, m.id, m.url_thumb
                           FROM `[prefix]_gallery_imgs` AS g
                           LEFT JOIN `[prefix]_media` m ON g.image_id = m.id
                           
                           WHERE g.id = '.$id;
        $imageRow = $this->db()->queryRow($sql);
        
            $entryModel = new ImageModel();
            $entryModel->setImageId($imageRow['url']);
            
        

        return $entryModel;
    }

    public function getImageById($id)
    {
        $gallery = $this->getImage(array('id' => $id));
        return $gallery;
    }

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
                ->fields(array('image_id' => $model->getImageId(),'cat' => $model->getCat()))
                ->where(array('id' => $model->getId()))
                ->execute();
        } else {
            $this->db()->insert('gallery_imgs')
                ->fields(array('image_id' => $model->getImageId(),'cat' => $model->getCat()))
                ->execute();
        }
    }

    public function getImageByGalleryId($id, $pagination = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS g.image_id,g.cat,g.id as imgid, m.url, m.id, m.url_thumb
                           FROM `[prefix]_gallery_imgs` AS g
                           LEFT JOIN `[prefix]_media` m ON g.image_id = m.id
                           
                           WHERE g.cat = '.$id.' ORDER BY g.id DESC
                           LIMIT '.implode(',',$pagination->getLimit());

        $imageArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        $entry = array();

        foreach ($imageArray as $entries) {
            $entryModel = new ImageModel();
            $entryModel->setImageId($entries['url']);
            $entryModel->setImageThumb($entries['url_thumb']);
            $entryModel->setId($entries['imgid']);
            $entry[] = $entryModel;
        }
        return $entry;
    }

    public function deleteById($id)
    {
            return $this->db()->delete('gallery_imgs')
            ->where(array('id' => $id))
            ->execute();
    }
}