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

        $userId = (int)$this->getRequest()->getParam('user');
        $profil = $userMapper->getUserById($userId);

        if ($profil === null) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'User', 'errorText' => 'notFound']);
        }

        $isUnrestricted = $this->getUser() !== null
            && $profil !== null
            && ($this->getUser()->getId() == $profil->getId()
                || $this->getUser()->isAdmin());

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('gallery'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index'])
            ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $userId])
            ->add($this->getTranslator()->trans('menuGallery'), ['action' => 'index', 'user' => $userId]);

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent($userId, 0, !$isUnrestricted));
        $this->getView()->set('unrestricted', $isUnrestricted);
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction()
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();
        $pagination = new \Ilch\Pagination();
        $userMapper = new UserMapper();

        $id = (int)$this->getRequest()->getParam('id');
        $userId = (int)$this->getRequest()->getParam('user');
        $gallery = $galleryMapper->getGalleryById($id);
        $profil = $userMapper->getUserById($userId);

        $isUnrestricted = $this->getUser() !== null
            && $profil !== null
            && ($this->getUser()->getId() == $profil->getId()
                || $this->getUser()->isAdmin());

        if (
            $profil === null
            || $gallery === null
            || (int)$gallery->getUserId() !== $userId
            || (!$isUnrestricted && !$galleryMapper->isEffectivelyPublic($id))
        ) {
            $this->redirect()
                ->withMessage('galleryNotAccessible', 'warning')
                ->to(['controller' => 'profil', 'action' => 'index', 'user' => $userId]);
        }

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('gallery'))
            ->add($gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery') . ' - ' . $gallery->getDesc());
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index', 'action' => 'index'])
            ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $userId])
            ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'gallery', 'action' => 'index', 'user' => $userId])
            ->add($gallery->getTitle(), ['action' => 'show', 'user' => $userId, 'id' => $id]);

        $pagination->setRowsPerPage(!$this->getConfig()->get('user_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('user_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination, !$isUnrestricted));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction()
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();
        $userMapper = new UserMapper();

        $id = (int)$this->getRequest()->getParam('id');
        $userId = (int)$this->getRequest()->getParam('user');

        $image = $imageMapper->getImageById($id);
        $gallery = ($image !== null) ? $galleryMapper->getGalleryById((int)$image->getCat()) : null;
        $profil = $userMapper->getUserById($userId);

        $isUnrestricted = $this->getUser() !== null
            && $profil !== null
            && ($this->getUser()->getId() == $profil->getId()
                || $this->getUser()->isAdmin());

        // Blocked views must not increment the visit counter.
        if (
            $image === null
            || $gallery === null
            || (int)$image->getUserId() !== $userId
            || (int)$gallery->getUserId() !== $userId
            || (!$isUnrestricted
                && ((int)$image->getIsPublic() !== 1
                    || !$galleryMapper->isEffectivelyPublic((int)$image->getCat())))
        ) {
            $this->redirect()
                ->withMessage('imageNotAccessible', 'warning')
                ->to(['controller' => 'profil', 'action' => 'index', 'user' => $userId]);
        }

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('gallery'))
            ->add($profil->getName())
            ->add($gallery->getTitle())
            ->add($image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery') . ' - ' . $gallery->getDesc());
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index', 'action' => 'index'])
            ->add($profil->getName(), ['controller' => 'profil', 'action' => 'index', 'user' => $userId])
            ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'gallery', 'action' => 'index', 'user' => $userId])
            ->add($gallery->getTitle(), ['action' => 'show', 'user' => $userId, 'id' => $gallery->getId()])
            ->add($image->getImageTitle(), ['action' => 'showimage', 'user' => $userId, 'id' => $id]);

        $model = new GalleryImageModel();
        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);
        $imageMapper->saveVisits($model);

        if ($this->getUser()) {
            if ($this->getRequest()->getPost('saveComment')) {
                $comments = new Comments();
                $key = 'user/gallery/showimage/user/' . $userId;

                if ($this->getRequest()->getPost('fkId')) {
                    $key .= '/id_c/' . $this->getRequest()->getPost('fkId');
                }

                $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                $this->redirect(['action' => 'showImage', 'user' => $userId, 'id' => $id]);
            }
            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'showimage', 'user' => $userId, 'id' => $id . '#comment_' . $commentId]);
            }
        }

        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('commentsKey', 'user/gallery/showimage/user/' . $userId . '/id/' . $id);
    }
}
