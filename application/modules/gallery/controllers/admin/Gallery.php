<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Image as ImageMapper;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;

class Gallery extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionGalleryInsertImage',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => 'javascript:media();'
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuGallery',
            $items
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
                ->add($this->getTranslator()->trans('gallery'), ['action' => 'index'])
                ->add($this->getTranslator()->trans($galleryTitle->getTitle()), ['action' => 'treatgallery', 'id' => $id]);

        if ($this->getRequest()->getPost('action') === 'delete') {
                foreach ($this->getRequest()->getPost('check_gallery') as $imageId) {
                    $imageMapper->deleteById($imageId);
                }
                $this->addMessage('deleteSuccess');
                $this->redirect(['action' => 'treatgallery','id' => $id]);
        }

        if ($this->getRequest()->getPost()) {
            foreach ($this->getRequest()->getPost('check_image') as $imageId ) {
                $catId = $this->getRequest()->getParam('id');
                $model = new \Modules\Gallery\Models\Image();
                $model->setImageId($imageId);
                $model->setCat($catId);
                $imageMapper->save($model);
            }
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('gallery_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('gallery_picturesPerPage'));
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

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('image', $imageMapper->getImageById($id));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $imageMapper = new ImageMapper();

            $id = $this->getRequest()->getParam('id');
            $imageMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'treatgallery', 'id' => $this->getRequest()->getParam('gallery')]);
        }
    }
}
