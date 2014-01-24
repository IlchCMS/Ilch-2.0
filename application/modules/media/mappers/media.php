<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Mappers;

use Media\Models\Media as MediaModel;

defined('ACCESS') or die('no direct access');

class Media extends \Ilch\Mapper
{
    public function getMediaList() 
    {
        $mediaArray = $this->db()->selectArray
        (
            '*',
            'media',
            '',
            array('id' => 'DESC')
        );

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
            $media[] = $entryModel;

        }

        return $media;
    }

    public function save(MediaModel $model)
    {
        $this->db()->insert
        (
            array
            (
                'url' => $model->getUrl(),
                'name' => $model->getName(),
                'datetime' => $model->getDatetime(),
                'ending' => $model->getEnding(),
            ),
            'media'
        );
    }	

    public function delImage($id) 
    {
        $this->db()->delete('media')
            ->where(array('id' => $id))
            ->execute();
    }
}
