<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Gallery\Controllers\Admin\Base as BaseController;

class Image extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuGalleryBack',
                'icon' => 'fa fa-arrow-left',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'gallery', 'action' => 'treatgallery', 'id' => $this->getRequest()->getParam('gallery')))
            )
        );
    }

    public function indexAction() 
    {
        
    }

    public function treatImageAction() 
    {
        $imageMapper = new ImageMapper();
        $id = (int)$this->getRequest()->getParam('id');
        $gallery = (int)$this->getRequest()->getParam('gallery');

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gallery'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('treatImage'), array('action' => 'treatimage', 'gallery' => $gallery, 'id' => $id));

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
}
