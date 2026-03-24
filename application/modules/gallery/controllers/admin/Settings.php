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

        // List of all Venobox settings keys
        $venoboxKeys = [
            'venoboxBgcolor', 'venoboxBorder', 'venoboxFitView',
            'venoboxInfiniteGallery', 'venoboxMaxWidth', 'venoboxNavigation', 'venoboxNavKeyboard',
            'venoboxNavTouch', 'venoboxNavSpeed', 'venoboxNumeration', 'venoboxOverlayColor',
            'venoboxPopup', 'venoboxRatio', 'venoboxShare', 'venoboxShareStyle',
            'venoboxSpinColor', 'venoboxSpinner', 'venoboxTitleattr', 'venoboxTitlePosition',
            'venoboxTitleStyle', 'venoboxToolsBackground', 'venoboxToolsColor'
        ];

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'picturesPerPage' => 'numeric|min:1',
                'pictureOfXInterval' => 'numeric|min:0|max:4',
                'pictureOfXRandom' => 'numeric|min:0|max:1',
                // Venobox numeric checks (for boolean/numeric values)
                'venoboxNumeration' => 'numeric|min:0|max:1',
                'venoboxInfiniteGallery' => 'numeric|min:0|max:1',
                'venoboxNavSpeed' => 'numeric',
            ]);

            if ($validation->isValid()) {
                // Standard Gallery Settings
                $this->getConfig()->set('gallery_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));

                $pictureOfXSource = is_array($this->getRequest()->getPost('pictureOfXSource')) ? implode(",", $this->getRequest()->getPost('pictureOfXSource')) : '';
                $this->getConfig()->set('gallery_pictureOfXSource', (empty($pictureOfXSource)) ? '' : $pictureOfXSource);
                $this->getConfig()->set('gallery_pictureOfXInterval', $this->getRequest()->getPost('pictureOfXInterval'));
                $this->getConfig()->set('gallery_pictureOfXRandom', $this->getRequest()->getPost('pictureOfXRandom'));

                // Venobox Settings Loop
                foreach ($venoboxKeys as $key) {
                    $value = $this->getRequest()->getPost($key);
                    // Always save config key with prefix 'gallery_'
                    $this->getConfig()->set('gallery_' . $key, $value);
                }

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        // Set view variables
        $this->getView()->set('picturesPerPage', $this->getConfig()->get('gallery_picturesPerPage'));
        $this->getView()->set('pictureOfXSource', explode(',', $this->getConfig()->get('gallery_pictureOfXSource') ?? ''));
        $this->getView()->set('pictureOfXInterval', $this->getConfig()->get('gallery_pictureOfXInterval'));
        $this->getView()->set('pictureOfXRandom', $this->getConfig()->get('gallery_pictureOfXRandom'));
        $this->getView()->set('galleries', $galleryMapper->getGalleryCatItem(1));

        // Pass Venobox variables to view
        foreach ($venoboxKeys as $key) {
            $this->getView()->set($key, $this->getConfig()->get('gallery_' . $key));
        }
    }
}

