<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Mappers\GalleryImage as GalleryImageMapper;
use Modules\User\Models\GalleryImage as GalleryImageModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;

class Gallery extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $profilMapper = new UserMapper();
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();

        $profil = $profilMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), array('controller' => 'index'))
                ->add($profil->getName(), array('controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($this->getTranslator()->trans('menuGallery'), array('action' => 'index', 'user' => $this->getRequest()->getParam('user')));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery'));
        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent($this->getRequest()->getParam('user'), 1, 0));
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction() 
    {
        $profilMapper = new UserMapper();
        $imageMapper = new GalleryImageMapper();
        $pagination = new \Ilch\Pagination();
        $galleryMapper = new GalleryMapper();

        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);
        $profil = $profilMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery').' - '.$gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), array('controller' => 'index'))
                ->add($profil->getName(), array('controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($this->getTranslator()->trans('menuGallery'), array('controller' => 'gallery', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($gallery->getTitle(), array('action' => 'show', 'user' => $this->getRequest()->getParam('user'), 'id' => $id));

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction() 
    {
        $profilMapper = new UserMapper();
        $commentMapper = new CommentMapper;
        $imageMapper = new GalleryImageMapper();
        $galleryMapper = new GalleryMapper();

        $id = $this->getRequest()->getParam('id');
        $galleryId = $this->getRequest()->getParam('gallery');
        $userId = $this->getRequest()->getParam('user');
        $gallery = $galleryMapper->getGalleryById($galleryId);
        $comments = $commentMapper->getCommentsByKey('user/gallery/showimage/user/'.$userId.'/gallery/'.$galleryId.'/id/'.$id);
        $image = $imageMapper->getImageById($id);
        $profil = $profilMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('gallery').' - '.$gallery->getTitle().' - '.$image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), array('controller' => 'index'))
                ->add($profil->getName(), array('controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($this->getTranslator()->trans('menuGallery'), array('controller' => 'gallery', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')))
                ->add($gallery->getTitle(), array('action' => 'show', 'user' => $this->getRequest()->getParam('user'), 'id' => $galleryId))
                ->add($image->getImageTitle(), array('action' => 'showimage', 'user' => $this->getRequest()->getParam('user'), 'gallery' => $galleryId, 'id' => $id));

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();

            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('user/gallery/showimage/user/'.$userId.'/gallery/'.$galleryId.'/id/'.$id.'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('user/gallery/showimage/user/'.$userId.'/gallery/'.$galleryId.'/id/'.$id);
            }
            $commentModel->setText($this->getRequest()->getPost('gallery_comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $model = new GalleryImageModel();
        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);
        $imageMapper->saveVisits($model);

        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('comments', $comments);
    }
}


