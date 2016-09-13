<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers;

use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Models\Image as ImageModel;
use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), ['action' => 'index']);

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent(1, 0));
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();
        $pagination = new \Ilch\Pagination();

        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);
        $pagination->setRowsPerPage(!$this->getConfig()->get('gallery_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('gallery_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), ['action' => 'index'])
                ->add($gallery->getTitle(), ['action' => 'show', 'id' => $id]);

        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();
        $commentMapper = new CommentMapper;
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        $id = $this->getRequest()->getParam('id');
        $galleryId = $this->getRequest()->getParam('gallery');
        $gallery = $galleryMapper->getGalleryById($galleryId);
        $image = $imageMapper->getImageById($id);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($this->getTranslator()->trans('image'))
                ->add($image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$image->getImageDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), ['action' => 'index'])
                ->add($gallery->getTitle(), ['action' => 'show', 'id' => $galleryId])
                ->add($image->getImageTitle(), ['action' => 'showimage', 'gallery' => $galleryId, 'id' => $id]);

        $model = new ImageModel();
        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);
        $imageMapper->saveVisits($model);

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('gallery/index/showimage/gallery/'.$galleryId.'/id/'.$id.'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('gallery/index/showimage/gallery/'.$galleryId.'/id/'.$id);
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
            $this->redirect(['action' => 'showImage', 'gallery' => $galleryId, 'id' => $id]);
        }

        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('config', $config);
        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('comments', $commentMapper->getCommentsByKey('gallery/index/showimage/gallery/'.$galleryId.'/id/'.$id));
    }
}
