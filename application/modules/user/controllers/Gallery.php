<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Controllers;

use Ilch\Comments;
use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Mappers\GalleryImage as GalleryImageMapper;
use Modules\User\Models\GalleryImage as GalleryImageModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
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
            $comments = new Comments();
            $key = 'user/gallery/showimage/user/'.$userId;

            if ($this->getRequest()->getPost('fkId')) {
                $key .= '/id_c/'.$this->getRequest()->getPost('fkId');
            }

            $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
            $this->redirect(['action' => 'showImage', 'user' => $userId, 'id' => $id]);
        }
        if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
            $commentId = $this->getRequest()->getParam('commentId');
            $comments = new Comments();

            $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
            $this->redirect(['action' => 'showimage', 'user' => $userId, 'id' => $id.'#comment_'.$commentId]);
        }

        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('commentsKey', 'user/gallery/showimage/user/'.$userId.'/id/'.$id);
    }
}
