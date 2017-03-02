<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

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
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuEvents',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'event_height'              => 'imageHeight',
                'event_width'               => 'imageWidth',
                'event_size'                => 'imageSizeBytes',
                'event_filetypes'           => 'imageAllowedFileExtensions',
                'event_google_maps_map_typ' => 'googleMapsMapTyp',
                'event_google_maps_zoom'    => 'googleMapsZoom'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'event_box_event_limit'     => 'required|numeric|min:1',
                'event_height'              => 'required|numeric|min:1',
                'event_width'               => 'required|numeric|min:1',
                'event_size'                => 'required|numeric|min:1',
                'event_filetypes'           => 'required',
                'event_google_maps_map_typ' => 'required',
                'event_google_maps_zoom'    => 'required|numeric|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('event_box_event_limit', $this->getRequest()->getPost('event_box_event_limit'));
                $this->getConfig()->set('event_height', $this->getRequest()->getPost('event_height'));
                $this->getConfig()->set('event_width', $this->getRequest()->getPost('event_width'));
                $this->getConfig()->set('event_size', $this->getRequest()->getPost('event_size'));
                $this->getConfig()->set('event_filetypes', $this->getRequest()->getPost('event_filetypes'));
                $this->getConfig()->set('event_google_maps_api_key', $this->getRequest()->getPost('event_google_maps_api_key'));
                $this->getConfig()->set('event_google_maps_map_typ', $this->getRequest()->getPost('event_google_maps_map_typ'));
                $this->getConfig()->set('event_google_maps_zoom', $this->getRequest()->getPost('event_google_maps_zoom'));
                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('event_box_event_limit', $this->getConfig()->get('event_box_event_limit'));
        $this->getView()->set('event_height', $this->getConfig()->get('event_height'));
        $this->getView()->set('event_width', $this->getConfig()->get('event_width'));
        $this->getView()->set('event_size', $this->getConfig()->get('event_size'));
        $this->getView()->set('event_filetypes', $this->getConfig()->get('event_filetypes'));
        $this->getView()->set('event_google_maps_api_key', $this->getConfig()->get('event_google_maps_api_key'));
        $this->getView()->set('event_google_maps_map_typ', $this->getConfig()->get('event_google_maps_map_typ'));
        $this->getView()->set('event_google_maps_zoom', $this->getConfig()->get('event_google_maps_zoom'));
    }
}
