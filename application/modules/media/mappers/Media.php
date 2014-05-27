<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Mappers;

use Modules\Media\Models\Media as MediaModel;

defined('ACCESS') or die('no direct access');

class Media extends \Ilch\Mapper
{
    public function getMediaList() 
    {
        $sql = 'SELECT m.id,m.url,m.url_thumb,m.name,m.datetime,m.ending,m.cat,c.cat_name
                FROM `[prefix]_media` as m
                LEFT JOIN [prefix]_media_cats as c ON m.cat = c.id
                ORDER by m.id DESC';

        $mediaArray = $this->db()->queryArray($sql);

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $entryModel->setCatName(($medias['cat_name']));
            $entryModel->setCatId(($medias['cat']));
            $media[] = $entryModel;

        }

        return $media;
    }

    public function getCatList() 
    {
        $mediaArray = $this->db()->select('*')
            ->from('media_cats')
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();
        

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();
        
        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setCatName(($medias['cat_name']));
            $media[] = $entryModel;

        }

        return $media;
    }

    public function save(MediaModel $model)
    {
        $this->db()->insert('media')
            ->values(array(
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'name' => $model->getName(),
                'datetime' => $model->getDatetime(),
                'ending' => $model->getEnding(),
                'cat' => '0',
                'cat_name' => 'Allgemein',
            ))
            ->execute();
    }	

    public function delImage($id) 
    {
        $mediaRow = $this->db()->select('*')
            ->from('media')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();
        if (file_exists($mediaRow['url'])) {
            unlink($mediaRow['url']);
        }
        if (file_exists($mediaRow['url_thumb'])) {
            unlink($mediaRow['url_thumb']);
        }
        $this->db()->delete('media')
            ->where(array('id' => $id))
            ->execute();
    }
}
