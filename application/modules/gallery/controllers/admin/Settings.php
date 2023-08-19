<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
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

        $this->getLayout()->addMenu
        (
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
                'venoboxInfinigall' => 'numeric|min:0|max:1'

            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('gallery_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
                $pictureOfXSource = is_array($data = $this->getRequest()->getPost('pictureOfXSource')) ? implode(",", $data) : '';


                $this->getConfig()->set('gallery_pictureOfXSource', (empty($pictureOfXSource)) ? '' : $pictureOfXSource);
                $this->getConfig()->set('gallery_pictureOfXInterval', $this->getRequest()->getPost('pictureOfXInterval'));

                // Settings Venobox
                $this->getConfig()->set('venoboxOverlayColor', $this->getRequest()->getPost('venoboxOverlayColor'));
                $this->getConfig()->set('venoboxNumeration', $this->getRequest()->getPost('venoboxNumeration'));
                $this->getConfig()->set('venoboxInfinigall', $this->getRequest()->getPost('venoboxInfinigall'));
                $this->getConfig()->set('venoboxBgcolor', $this->getRequest()->getPost('venoboxBgcolor'));
                $this->getConfig()->set('venoboxBorder', $this->getRequest()->getPost('venoboxBorder'));
                $this->getConfig()->set('venoboxTitleattr', $this->getRequest()->getPost('venoboxTitleattr'));


                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('picturesPerPage', $this->getConfig()->get('gallery_picturesPerPage'));
        $this->getView()->set('pictureOfXSource', explode(',', $this->getConfig()->get('gallery_pictureOfXSource')));
        $this->getView()->set('pictureOfXInterval', $this->getConfig()->get('gallery_pictureOfXInterval'));
        $this->getView()->set('pictureOfXRandom', $this->getConfig()->get('gallery_pictureOfXRandom'));
        $this->getView()->set('galleries', $galleryMapper->getGalleryCatItem(1));
        $this->getView()->set('venoboxOverlayColor', $this->getConfig()->get('venoboxOverlayColor') );
        $this->getView()->set('venoboxNumeration', $this->getConfig()->get('venoboxNumeration') );
        $this->getView()->set('venoboxInfinigall', $this->getConfig()->get('venoboxInfinigall') );
        $this->getView()->set('venoboxBgcolor', $this->getConfig()->get('venoboxBgcolor') );
        $this->getView()->set('venoboxBorder', $this->getConfig()->get('venoboxBorder') );
        $this->getView()->set('venoboxTitleattr', $this->getConfig()->get('venoboxTitleattr') );




    }
}
