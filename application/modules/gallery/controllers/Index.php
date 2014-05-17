<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Gallery\Controllers;

use Gallery\Mappers\Gallery as GalleryMapper;
use Gallery\Mappers\Image as ImageMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend 
{
    public function indexAction() 
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'));
        $galleryMapper = new GalleryMapper();
        $galleryItems = $galleryMapper->getGalleryItemsByParent(1, 0);
        $this->getView()->set('galleryItems', $galleryItems);
        
        $this->getView()->set('galleryMapper', $galleryMapper);
	}

    public function showAction() 
    {
        $imagemapper = new ImageMapper();
        $pagination = new \Ilch\Pagination();
        $galleryMapper = new GalleryMapper();
        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);

        
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'))
                ->add($gallery->getTitle(), array('action' => 'show', 'id' => $id));
        
        $pagination->setPage($this->getRequest()->getParam('page'));
        
        $this->getView()->set('image', $imagemapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }
}