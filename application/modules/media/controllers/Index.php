<?php

/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Gallery\Controllers;

use Gallery\Models\Page as GalleryModels;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend {

    public function indexAction() {
	
		$this->model = new GalleryModels();
        $cats = $this->model->getCats();

        return $this->getView()->set('cats', $cats);
        
    }
	
	public function showAction() {
		$id = $this->getRequest()->getParam('id');
		$this->model = new GalleryModels();
        $entrys = $this->model->showImage($id);

        return $this->getView()->set('entrys', $entrys);
        
    }

    
}
