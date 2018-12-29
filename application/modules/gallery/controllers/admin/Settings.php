<?php
/**
 * @copyright Ilch 2.0
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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-cogs',
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
                'pictureOfXRandom' => 'numeric|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('gallery_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
                $pictureOfXSource = implode(',', $this->getRequest()->getPost('pictureOfXSource'));
                $this->getConfig()->set('gallery_pictureOfXSource', (empty($pictureOfXSource)) ? '' : $pictureOfXSource);
                $this->getConfig()->set('gallery_pictureOfXInterval', $this->getRequest()->getPost('pictureOfXInterval'));
                $this->getConfig()->set('gallery_pictureOfXRandom', $this->getRequest()->getPost('pictureOfXRandom'));
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
    }
}
