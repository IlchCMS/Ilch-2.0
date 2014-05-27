<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Controllers\Admin\Base as BaseController;

defined('ACCESS') or die('no direct access');

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
        $imagemapper = new ImageMapper();
        $pagination = new \Ilch\Pagination();
        $gallerymapper = new GalleryMapper();
        $id = $this->getRequest()->getParam('id');
        $galleryTitle = $gallerymapper->getGalleryById($id);

        if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_gallery') as $imageId) {
                    $imagemapper->deleteById($imageId);
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
                $imagemapper->save($model);
            }
        }
        $pagination->setPage($this->getRequest()->getParam('page'));
		$this->getView()->set('image', $imagemapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('galleryTitle', $galleryTitle->getTitle());
    }

    public function delAction()
    {
        if ($this->getRequest()) {
            $imageMapper = new ImageMapper();
            $id = $this->getRequest()->getParam('id');
            $imageMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(array('action' => 'treatgallery','id' => $this->getRequest()->getParam('gallery')));
        }
    }
}