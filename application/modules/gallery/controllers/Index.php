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
use Modules\Gallery\Models\Image as ImageModel;

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

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery'));
        $this->getView()->set('galleryItems', $galleryItems);
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction() 
    {
        $imageMapper = new ImageMapper();
        $pagination = new \Ilch\Pagination();
        $galleryMapper = new GalleryMapper();
        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery').' - '.$gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'))
                ->add($gallery->getTitle(), array('action' => 'show', 'id' => $id));
        
        $pagination->setPage($this->getRequest()->getParam('page'));
        
        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction() 
    {
        $commentMapper = new CommentMapper;
        $imageMapper = new ImageMapper();
        $galleryMapper = new GalleryMapper();

        $id = $this->getRequest()->getParam('id');
        $galleryId = $this->getRequest()->getParam('gallery');

        if ($this->getRequest()->getPost('gallery_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('gallery/index/showimage/gallery/'.$galleryId.'/id/'.$id);
            $commentModel->setText($this->getRequest()->getPost('gallery_comment_text'));

            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $gallery = $galleryMapper->getGalleryById($galleryId);
        $comments = $commentMapper->getCommentsByKey('gallery/index/showimage/gallery/'.$galleryId.'/id/'.$id);
        $image = $imageMapper->getImageById($id);

        $model = new ImageModel();

        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);

        $imageMapper->saveVisits($model);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery').' - '.$this->getTranslator()->trans('image').' - '.$image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$image->getImageDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), array('action' => 'index'))
                ->add($gallery->getTitle(), array('action' => 'show', 'id' => $galleryId))
                ->add($image->getImageTitle(), array('action' => 'showimage', 'gallery' => $galleryId, 'id' => $id));

        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('comments', $comments);
    }
}
