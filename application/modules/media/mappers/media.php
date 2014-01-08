<?php
/**
 * Holds Gallery.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Mappers;

use Media\Models\Media as MediaModel;

defined('ACCESS') or die('no direct access');

/**
 * The gallery mapper class.
 *
 * @package ilch
 */
class Media extends \Ilch\Mapper
{
    public function getMediaList() {
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
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setDescription($medias['description']);
            $media[] = $entryModel;

        }

        return $media;
    }
    
    public function saveEntry(array $datas) {

        $sql = 'INSERT INTO `[prefix]_media`
            ( ';
        $sqlFields = array();
        $sqlValues = array();

        foreach ($datas as $key => $value) {
            $sqlFields[] = '`' . $key . '`';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        foreach ($datas as $key => $value) {
            $sqlValues[] = '"' . $this->db()->escape($value) . '"';
        }

        $sql .= implode(',', $sqlValues) . ')';

        $result = $this->db()->query($sql);
        return $result; // true|false
    }
	
	
	public function saveImage(array $datas) {

        $sql = 'INSERT INTO `[prefix]_media`
            ( ';
        $sqlFields = array();
        $sqlValues = array();

        foreach ($datas as $key => $value) {
            $sqlFields[] = '`' . $key . '`';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        foreach ($datas as $key => $value) {
            $sqlValues[] = '"' . $this->db()->escape($value) . '"';
        }

        $sql .= implode(',', $sqlValues) . ')';

        $result = $this->db()->query($sql);
        return $result; // true|false
    }
	
	
	    public function showImage() {
        $sql = "SELECT * FROM `[prefix]_media` ";
        $entries = $this->db()->queryArray($sql);


        return $entries; // true|false            
    }
	
	
	public function delImage($id) {
		
		     $this->db()->delete('media', array('id' => $id));
        return ;
	
	}
}
