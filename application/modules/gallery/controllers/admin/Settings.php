<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Ilch\Validation;

class Settings extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuGallery',
            $items
        );
    }

    public function indexAction()
    {
        $galleryMapper = new GalleryMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gallery'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'picturesPerPage' => 'numeric|min:1',
                'pictureOfXInterval' => 'numeric|min:0|max:4',
                'pictureOfXRandom' => 'numeric|min:0|max:1',
                'venoboxNumeration' => 'numeric|min:0|max:1',
                'venoboxInfiniteGallery' => 'numeric|min:0|max:1',
                'venoboxBorder' => 'numeric',
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('gallery_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
                $pictureOfXSource = is_array($this->getRequest()->getPost('pictureOfXSource')) ? implode(",", $this->getRequest()->getPost('pictureOfXSource')) : '';
                $this->getConfig()->set('gallery_pictureOfXSource', (empty($pictureOfXSource)) ? '' : $pictureOfXSource);
                $this->getConfig()->set('gallery_pictureOfXInterval', $this->getRequest()->getPost('pictureOfXInterval'));
                $this->getConfig()->set('gallery_pictureOfXRandom', $this->getRequest()->getPost('pictureOfXRandom'));
                // Settings Venobox
                $this->getConfig()->set('gallery_venoboxOverlayColor', $this->getRequest()->getPost('venoboxOverlayColor'));
                $this->getConfig()->set('gallery_venoboxNumeration', $this->getRequest()->getPost('venoboxNumeration'));
                $this->getConfig()->set('gallery_venoboxInfiniteGallery', $this->getRequest()->getPost('venoboxInfiniteGallery'));
                $this->getConfig()->set('gallery_venoboxBgcolor', $this->getRequest()->getPost('venoboxBgcolor'));
                $this->getConfig()->set('gallery_venoboxBorder', $this->getRequest()->getPost('venoboxBorder'));
                $this->getConfig()->set('gallery_venoboxTitleattr', $this->getRequest()->getPost('venoboxTitleattr'));

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('picturesPerPage', $this->getConfig()->get('gallery_picturesPerPage'));
        $this->getView()->set('pictureOfXSource', explode(',', $this->getConfig()->get('gallery_pictureOfXSource') ?? ''));
        $this->getView()->set('pictureOfXInterval', $this->getConfig()->get('gallery_pictureOfXInterval'));
        $this->getView()->set('pictureOfXRandom', $this->getConfig()->get('gallery_pictureOfXRandom'));
        $this->getView()->set('galleries', $galleryMapper->getGalleryCatItem(1));
        $this->getView()->set('venoboxOverlayColor', $this->getConfig()->get('gallery_venoboxOverlayColor'));
        $this->getView()->set('venoboxNumeration', $this->getConfig()->get('gallery_venoboxNumeration'));
        $this->getView()->set('venoboxInfiniteGallery', $this->getConfig()->get('gallery_venoboxInfiniteGallery'));
        $this->getView()->set('venoboxBgcolor', $this->getConfig()->get('gallery_venoboxBgcolor'));
        $this->getView()->set('venoboxBorder', $this->getConfig()->get('gallery_venoboxBorder'));
        $this->getView()->set('venoboxTitleattr', $this->getConfig()->get('gallery_venoboxTitleattr'));
    }
}
