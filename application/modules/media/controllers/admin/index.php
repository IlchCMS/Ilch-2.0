<?php

/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Controllers\Admin;

use Media\Mappers\Media as MediaMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuMedia',
            array
            (
                array
                (
                    'name' => 'allMedias',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                )
            )
        );
        
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionAddNew',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'upload'))
            )
        );
    }

    public function indexAction() 
    {
        $MediaMapper = new MediaMapper();
        $this->getView()->set('medias', $MediaMapper->getMediaList());
    }
		
    public function uploadAction() 
    {
        return $this->getView();
    }
	
	public function saveimageAction() {
		$allowed = array('png', 'jpg', 'gif');
		$url = 'application/modules/media/static/upload/';
		
		
		    
		
			if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

				if(move_uploaded_file($_FILES['upl']['tmp_name'], $url.$_FILES['upl']['name'])){
	 
				}
				
			
			
				
				
			$entryImage = array(
                    'url' => $url.trim($_FILES['upl']['name']),
                );
                $this->model = new MediaMapper();
                $this->model->saveImage($entryImage);
				
				
				
				
			}
	}


    public function showAction() {
		$id = $this->getRequest()->getParam('id');
		$this->model = new MediaModels();
        $entrys = $this->model->showImage($id);

        return $this->getView()->set('entrys', $entrys);
        
    }	
	
	public function delAction()
    {
        $MediaMapper = new MediaMapper();
        $MediaMapper->delImage($this->getRequest()->getParam('id'));
        $this->addMessage('deleteSuccess');
        $this->redirect(array('action' => 'index'));
        
    }
	
	
}
