<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.15.0
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Maps as MapsMapper;
use Modules\War\Models\Maps as MapsModel;
use Ilch\Validation;

class Maps extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa-solid fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index']),
                [
                    'name' => 'menuActionNewMap',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuGameIcons',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[3][0]['active'] = true;
        } else {
            $items[3]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $mapsMapper = new MapsMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageMaps'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_maps')) {
            foreach ($this->getRequest()->getPost('check_maps') as $groupId) {
                $mapsMapper->delete($groupId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('maps', $mapsMapper->getList($pagination))
            ->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $mapsMapper = new MapsMapper();
        $mapsModel = new MapsModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageMaps'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatMaps'), ['action' => 'treat']);

            $mapsModel = $mapsMapper->getEntryById((int)$this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageMaps'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewMaps'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'mapsName' => 'required|unique:war_maps,name'
            ]);

            if ($validation->isValid()) {
                $mapsModel->setName($this->getRequest()->getPost('mapsName'));
                $mapsMapper->save($mapsModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($mapsModel->getId()?['id' => $mapsModel->getId()]:[])));
        }

        $this->getView()->set('maps', $mapsModel);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $mapsMapper = new MapsMapper();
            $mapsMapper->delete((int)$this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
