<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Gallery\Mappers\Image as ImageMapper;

class Image extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuGalleryBack',
                    'active' => false,
                    'icon' => 'fa-solid fa-arrow-left',
                    'url' => $this->getLayout()->getUrl(['controller' => 'gallery', 'action' => 'treatgallery', 'id' => $this->getRequest()->getParam('gallery')])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treatgallery') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuGallery',
            $items
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
                ->add($this->getTranslator()->trans('gallery'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatImage'), ['action' => 'treatimage', 'gallery' => $gallery, 'id' => $id]);

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
}
