<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Mappers\GalleryImage as GalleryImageMapper;
use Modules\User\Models\GalleryImage as GalleryImageModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

class Gallery extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();
        $userMapper = new UserMapper();

        $profil = $userMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index'])
                ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
                ->add($this->getTranslator()->trans('menuGallery'), ['action' => 'index', 'user' => $this->getRequest()->getParam('user')]);

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent($this->getRequest()->getParam('user'), 0));
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();
        $pagination = new \Ilch\Pagination();
        $userMapper = new UserMapper();

        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);
        $profil = $userMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index', 'action' => 'index'])
                ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
                ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'gallery', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
                ->add($gallery->getTitle(), ['action' => 'show', 'user' => $this->getRequest()->getParam('user'), 'id' => $id]);

        $pagination->setRowsPerPage(!$this->getConfig()->get('user_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('user_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction() 
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();
        $commentMapper = new CommentMapper;
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        $id = $this->getRequest()->getParam('id');
        $userId = $this->getRequest()->getParam('user');
        $image = $imageMapper->getImageById($id);
        $gallery = $galleryMapper->getGalleryById($image->getCat());
        $profil = $userMapper->getUserById($this->getRequest()->getParam('user'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($profil->getName())
                ->add($gallery->getTitle())
                ->add($image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery').' - '.$gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index', 'action' => 'index'])
                ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
                ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'gallery', 'action' => 'index', 'user' => $this->getRequest()->getParam('user')])
                ->add($gallery->getTitle(), ['action' => 'show', 'user' => $this->getRequest()->getParam('user'), 'id' => $gallery->getId()])
                ->add($image->getImageTitle(), ['action' => 'showimage', 'user' => $this->getRequest()->getParam('user'), 'id' => $id]);

        $model = new GalleryImageModel();
        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);
        $imageMapper->saveVisits($model);

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('user/gallery/showimage/user/'.$userId.'/id/'.$id.'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('user/gallery/showimage/user/'.$userId.'/id/'.$id);
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
            $this->redirect(['action' => 'showImage', 'user' => $userId, 'id' => $id]);
        }
        if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
            $id = $this->getRequest()->getParam('id');
            $commentId = $this->getRequest()->getParam('commentId');
            $oldComment = $commentMapper->getCommentById($commentId);

            $commentModel = new CommentModel();
            $commentModel->setId($commentId);
            if ($this->getRequest()->getParam('key') === 'up') {
                $commentModel->setUp($oldComment->getUp()+1);
            } else {
                $commentModel->setDown($oldComment->getDown()+1);
            }
            $commentModel->setVoted($oldComment->getVoted().$this->getUser()->getId().',');
            $commentMapper->saveLike($commentModel);

            $this->redirect(['action' => 'showimage', 'user' => $userId, 'id' => $id.'#comment_'.$commentId]);
        }

        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('config', $config);
        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('comments', $commentMapper->getCommentsByKey('user/gallery/showimage/user/'.$userId.'/id/'.$id));
    }
}
