<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Controllers\Admin\Base as BaseController;

class Gallery extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionGalleryInsertImage',
                'icon' => 'fa fa-plus-circle',
                'url'  => 'javascript:media();'
            )
        );
    }

    public function indexAction() 
    {
        
    }

    public function treatGalleryAction() 
    {
        $imageMapper = new ImageMapper();
        $pagination = new \Ilch\Pagination();
        $galleryMapper = new GalleryMapper();
        $id = $this->getRequest()->getParam('id');
        $galleryTitle = $galleryMapper->getGalleryById($id);

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gallery'), array('action' => 'index'))
                ->add($this->getTranslator()->trans($galleryTitle->getTitle()), array('action' => 'treatgallery', 'id' => $id));

        if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_gallery') as $imageId) {
                    $imageMapper->deleteById($imageId);
                }
                $this->addMessage('deleteSuccess');
                $this->redirect(array('action' => 'treatgallery','id' => $id));
        }

        if ($this->getRequest()->getPost()) {
            foreach($this->getRequest()->getPost('check_image') as $imageId ) {
                $catId = $this->getRequest()->getParam('id');
                $model = new \Modules\Gallery\Models\Image();
                $model->setImageId($imageId);
                $model->setCat($catId);
                $imageMapper->save($model);
            }
        }

        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('galleryTitle', $galleryTitle->getTitle());
    }

    public function treatImageAction() 
    {
        $imageMapper = new ImageMapper();
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->getPost()) {
            $imageTitle = $this->getRequest()->getPost('imageTitle');
            $imageDesc = $this->getRequest()->getPost('imageDesc');
            $model = new \Modules\Gallery\Models\Image();
            $model->setId($id);
            $model->setImageTitle($imageTitle);
            $model->setImageDesc($imageDesc);
            $imageMapper->saveImageTreat($model);

            $this->addMessage('Success');
        }

        $this->getView()->set('image', $imageMapper->getImageById($id));
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $imageMapper = new ImageMapper();
            $id = $this->getRequest()->getParam('id');

            $imageMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(array('action' => 'treatgallery', 'id' => $this->getRequest()->getParam('gallery')));
        }
    }
}
