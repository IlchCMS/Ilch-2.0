<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers;

use Ilch\Comments;
use Ilch\Controller\Frontend;
use Ilch\Pagination;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Models\Image as ImageModel;
use Modules\Gallery\Mappers\Image as ImageMapper;

class Index extends Frontend
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

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent(0));
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
    }

    public function showAction()
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();
        $pagination = new Pagination();

        $id = $this->getRequest()->getParam('id');

        if (empty($id) || !is_numeric($id)) {
            $this->addMessage('galleryNotFound', 'danger');
            $this->redirect(['action' => 'index']);
            return;
        }

        $gallery = $galleryMapper->getGalleryById($id);

        if (empty($gallery)) {
            $this->addMessage('galleryNotFound', 'danger');
            $this->redirect(['action' => 'index']);
            return;
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('gallery_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('gallery_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($gallery->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery') . ' - ' . $gallery->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), ['action' => 'index'])
                ->add($gallery->getTitle(), ['action' => 'show', 'id' => $id]);

        // Venobox instance options
        $venoboxOptions = [
            'numeration' => $this->getConfig()->get('gallery_venoboxNumeration'),
            'infinigall' => $this->getConfig()->get('gallery_venoboxInfiniteGallery'),
            'bgcolor' => $this->getConfig()->get('gallery_venoboxBgcolor'),
            'overlayColor' => $this->getConfig()->get('gallery_venoboxOverlayColor'),
            'border' => $this->getConfig()->get('gallery_venoboxBorder'),
            'titleattr' => $this->getConfig()->get('gallery_venoboxTitleattr'),
        ];
        $this->getView()->set('venoboxOptions', $venoboxOptions);
        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showImageAction()
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new ImageMapper();

        $id = $this->getRequest()->getParam('id');

        if (empty($id) || !is_numeric($id)) {
            $this->addMessage('imageNotFound', 'danger');
            $this->redirect(['action' => 'index']);
            return;
        }

        $image = $imageMapper->getImageById($id);

        if (empty($image)) {
            $this->addMessage('imageNotFound', 'danger');
            $this->redirect(['action' => 'index']);
            return;
        }

        $gallery = $galleryMapper->getGalleryById($image->getGalleryId());

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('gallery'))
                ->add($gallery->getTitle())
                ->add($image->getImageTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('gallery') . ' - ' . $image->getImageDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuGalleryOverview'), ['action' => 'index'])
                ->add($gallery->getTitle(), ['action' => 'show', 'id' => $gallery->getId()])
                ->add($image->getImageTitle(), ['action' => 'showimage', 'id' => $id]);

        $model = new ImageModel();
        $model->setImageId($image->getImageId());
        $model->setVisits($image->getVisits() + 1);
        $imageMapper->saveVisits($model);

        if ($this->getUser()) {
            if ($this->getRequest()->getPost('saveComment')) {
                $comments = new Comments();
                $key = 'gallery/index/showimage/id/' . $id;

                if ($this->getRequest()->getPost('fkId')) {
                    $key .= '/id_c/' . $this->getRequest()->getPost('fkId');
                }

                $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                $this->redirect(['action' => 'showimage', 'id' => $id]);
            }
            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'showimage', 'id' => $id . '#comment_' . $commentId]);
            }
        }
        $this->getView()->set('image', $imageMapper->getImageById($id));
        $this->getView()->set('commentsKey', 'gallery/index/showimage/id/' . $id);
    }
}
