<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers;

use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend 
{
    public function indexAction() 
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'));
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();
        $galleryItems = $galleryMapper->getGalleryItemsByParent(1, 0);
        $this->getView()->set('galleryItems', $galleryItems);
        
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
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

    public function showImageAction() 
    {
        $commentMapper = new CommentMapper;
        $imagemapper = new ImageMapper();
        $galleryMapper = new GalleryMapper();

        if ($this->getRequest()->getPost('gallery_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('gallery_'.$this->getRequest()->getParam('id'));
            $commentModel->setText($this->getRequest()->getPost('gallery_comment_text'));

            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $id = $this->getRequest()->getParam('id');
        $galleryid = $this->getRequest()->getParam('gallery');
        $gallery = $galleryMapper->getGalleryById($galleryid);
        $comments = $commentMapper->getCommentsByKey('gallery_'.$this->getRequest()->getParam('id'));

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'))
                ->add($gallery->getTitle(), array('action' => 'show', 'id' => $galleryid));

        $this->getView()->set('image', $imagemapper->getImageById($id));
        $this->getView()->set('comments', $comments);
    }
}
