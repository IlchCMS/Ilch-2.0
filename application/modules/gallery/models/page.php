<?php

/**
 * Guestbook class
 *
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Gallery\Models;

defined('ACCESS') or die('no direct access');

class Page extends \Ilch\Mapper {

    public function getCats() {
        $sql = "SELECT `id`,`cat`, `name`,`besch` FROM `[prefix]_gallery_cats` ORDER by `id` DESC";
        $entries = $this->db()->queryArray($sql);


        return $entries; // true|false            
    }
	
	public function catname($id) {
		
		$sql = 'SELECT name FROM [prefix]_gallery_cats WHERE id = '.$id ;
		$name = $this->db()->queryArray($sql);
		
		return $name;
	
	}
	
	public function saveEntry(array $datas) {

        $sql = 'INSERT INTO `[prefix]_gallery_cats`
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

        $sql = 'INSERT INTO `[prefix]_gallery_imgs`
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
	
	
	    public function showImage($id) {
        $sql = "SELECT `id`,`cat`, `url` FROM `[prefix]_gallery_imgs` WHERE cat=".$id;
        $entries = $this->db()->queryArray($sql);


        return $entries; // true|false            
    }
	
	
	public function delImage($id) {
		
		     $this->db()->delete('gallery_imgs', array('id' => $id));
        return ;
	
	}


}
