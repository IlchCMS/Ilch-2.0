<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fas fa-cogs',
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
        $userGroupMapper = new UserGroupMapper();

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
                'event_upcoming_event_limit'=> 'required|numeric|min:1',
                'event_current_event_limit' => 'required|numeric|min:1',
                'event_past_event_limit'    => 'required|numeric|min:1',
                'event_height'              => 'required|numeric|min:1',
                'event_width'               => 'required|numeric|min:1',
                'event_size'                => 'required|numeric|min:1',
                'event_filetypes'           => 'required',
                'event_google_maps_map_typ' => 'required',
                'event_google_maps_zoom'    => 'required|numeric|min:1'
            ]);

            if ($validation->isValid()) {
                $groupAccesses = implode(',', $this->getRequest()->getPost('event_add_entries_accesses'));
                $membersAccesses = implode(',', $this->getRequest()->getPost('event_show_members_accesses'));
                $extensionBlacklist = explode(' ', $this->getConfig()->get('media_extensionBlacklist'));
                $imageExtensions = explode(' ', strtolower($this->getRequest()->getPost('event_filetypes')));

                if (!is_in_array($extensionBlacklist, $imageExtensions)) {
                    $this->getConfig()->set('event_add_entries_accesses', $groupAccesses)
                        ->set('event_show_members_accesses', $membersAccesses)
                        ->set('event_box_event_limit', $this->getRequest()->getPost('event_box_event_limit'))
                        ->set('event_upcoming_event_limit', $this->getRequest()->getPost('event_upcoming_event_limit'))
                        ->set('event_current_event_limit', $this->getRequest()->getPost('event_current_event_limit'))
                        ->set('event_past_event_limit', $this->getRequest()->getPost('event_past_event_limit'))
                        ->set('event_height', $this->getRequest()->getPost('event_height'))
                        ->set('event_width', $this->getRequest()->getPost('event_width'))
                        ->set('event_size', $this->getRequest()->getPost('event_size'))
                        ->set('event_filetypes', $this->getRequest()->getPost('event_filetypes'))
                        ->set('event_google_maps_api_key', $this->getRequest()->getPost('event_google_maps_api_key'))
                        ->set('event_google_maps_map_typ', $this->getRequest()->getPost('event_google_maps_map_typ'))
                        ->set('event_google_maps_zoom', $this->getRequest()->getPost('event_google_maps_zoom'));

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['action' => 'index']);
                } else {
                    $this->redirect()
                        ->withMessage('forbiddenExtension', 'danger')
                        ->to(['action' => 'index']);
                }
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('userGroupList', $userGroupMapper->getGroupList())
            ->set('event_add_entries_accesses', $this->getConfig()->get('event_add_entries_accesses'))
            ->set('event_show_members_accesses', $this->getConfig()->get('event_show_members_accesses'))
            ->set('event_box_event_limit', $this->getConfig()->get('event_box_event_limit'))
            ->set('event_upcoming_event_limit', $this->getConfig()->get('event_upcoming_event_limit'))
            ->set('event_current_event_limit', $this->getConfig()->get('event_current_event_limit'))
            ->set('event_past_event_limit', $this->getConfig()->get('event_past_event_limit'))
            ->set('event_height', $this->getConfig()->get('event_height'))
            ->set('event_width', $this->getConfig()->get('event_width'))
            ->set('event_size', $this->getConfig()->get('event_size'))
            ->set('event_filetypes', $this->getConfig()->get('event_filetypes'))
            ->set('event_google_maps_api_key', $this->getConfig()->get('event_google_maps_api_key'))
            ->set('event_google_maps_map_typ', $this->getConfig()->get('event_google_maps_map_typ'))
            ->set('event_google_maps_zoom', $this->getConfig()->get('event_google_maps_zoom'));
        }
}
