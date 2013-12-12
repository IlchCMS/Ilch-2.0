<?php

/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Gallery\Controllers\Admin;

use Gallery\Models\Page as GalleryModels;


defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin {

    public function indexAction() {
    
		$this->model = new GalleryModels();
        $cats = $this->model->getCats();

        return $this->getView()->set('cats', $cats);

    }
	
    public function newcatAction() {
        
    }
	
	public function newcatEntryAction() {
	if (isset($_POST['saveEntry']) && isset($_POST['name']) && isset($_POST['besch'])) {
            if (empty($_POST['besch']) or empty($_POST['name'])) {
                $this->redirect(array('action' => 'error'));
           } else {

                $entryDatas = array(
                    'name' => trim($this->getRequest()->getPost('name')),
                    'besch' => trim($this->getRequest()->getPost('besch'))                    
                );
                $this->model = new GalleryModels();
                $this->model->saveEntry($entryDatas);
                $this->redirect(array('action' => 'index'));
            }
        }
      $this->redirect(array('action' => 'index'));  
    }
	
    public function uploadAction() {
	
	$id = $this->getRequest()->getParam('id');
	
	$catid = new GalleryModels();
	$catname = $catid->catname($id);
	
	return $this->getView()->set('catname', $catname);
        
    }
	
	public function saveimageAction() {
		$id = $this->getRequest()->getParam('id');
		$allowed = array('png', 'jpg', 'gif');
		$url = 'img/modules/gallery/upload/';
		
		
		    
		
			if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

				if(move_uploaded_file($_FILES['upl']['tmp_name'], $url.$_FILES['upl']['name'])){
	 
				}
				
			
			
				
				
			$entryImage = array(
                    'url' => $url.trim($_FILES['upl']['name']),
					'cat' => $id
                );
                $this->model = new GalleryModels();
                $this->model->saveImage($entryImage);
				
				
				
				
			}
	}


    public function showAction() {
		$id = $this->getRequest()->getParam('id');
		$this->model = new GalleryModels();
        $entrys = $this->model->showImage($id);

        return $this->getView()->set('entrys', $entrys);
        
    }	
	
	public function delAction() {
    
	    $id = $this->getRequest()->getParam('id');
		$cat = $this->getRequest()->getParam('cat');
		
		$this->model = new GalleryModels();
        $this->model->delImage($id);

           return $this->redirect(array('action' =>  'show' , 'id' => $cat));
            
    }
	
	
}
