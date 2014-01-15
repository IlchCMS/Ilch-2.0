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
    
    /**
     * Inserts media entry.
     *
     * @param MediaModel $model
     */
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
	
    public function delImage($id) {
		
		     $this->db()->delete('media', array('id' => $id));
        return ;
	
	}

    function resize_image($updir,$img){ 
        
        $max_width = "300";
        $max_height = "150";
        $arr_image_details = getimagesize("$updir" . "$img"); 
        $width = $arr_image_details[0];
        $height = $arr_image_details[1];
        $thumb_folder = "thumb";
               
        $new_width = "";
        $new_height = "";
       
        $with_scale = $width/$max_width;
        $height_scale = $height/$max_height;   
               
        if($with_scale > $height_scale){
                $new_width = $max_width;
                $new_height = ($max_width/$width) * $height;                   
               
        }else{
                $new_height = $max_height;
                $new_width = ($max_height/$height) * $width;                   
               
        }      

        $x_mid  = $new_width / 2;
        $y_mid  = $new_height / 2;
        
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {

            $newImage = imagecreatetruecolor($new_width,$new_height);
            $source = $imgcreatefrom("$updir" . "$img");
            imagecopyresampled($newImage,$source,0,0,0,0,$new_width,$new_height,$width,$height);
            $final = imagecreatetruecolor($max_width, $max_height);
            imagecopyresampled($final, $newImage, 0, 0, ($x_mid - ($max_width / 2)), ($y_mid - ($max_height / 2)), $max_width, $max_height, $max_width, $max_height);
            $bg_color = imagecolorallocate ($final, 0, 0, 0);
            imagecolortransparent($final,$bg_color); 
            $imgt($final, "$updir" . "$thumb_folder" ."/". "$img");
            
            $this->db()->update
                (
                    array
                    (
                        'url_thumb' => "$updir" . "$thumb_folder" ."/". "$img",
                    ),
                    'media',
                    array
                    (
                        'url' => "$updir" . "$img",
                        
                    )
                );
        }
    }
}
