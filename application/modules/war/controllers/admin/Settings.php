<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcons',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'warsPerPage' => 'numeric|integer|min:1',
                'enemiesPerPage' => 'numeric|integer|min:1',
                'groupsPerPage' => 'numeric|integer|min:1',
                'boxNextWarLimit' => 'numeric|integer|min:1',
                'boxLastWarLimit' => 'numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('war_warsPerPage', $this->getRequest()->getPost('warsPerPage'))
                    ->set('war_enemiesPerPage', $this->getRequest()->getPost('enemiesPerPage'))
                    ->set('war_groupsPerPage', $this->getRequest()->getPost('groupsPerPage'))
                    ->set('war_boxNextWarLimit', $this->getRequest()->getPost('boxNextWarLimit'))
                    ->set('war_boxLastWarLimit', $this->getRequest()->getPost('boxLastWarLimit'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('warsPerPage', $this->getConfig()->get('war_warsPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('enemiesPerPage', $this->getConfig()->get('war_enemiesPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('groupsPerPage', $this->getConfig()->get('war_groupsPerPage') ?? $this->getConfig()->get('defaultPaginationObjects'))
            ->set('boxNextWarLimit', $this->getConfig()->get('war_boxNextWarLimit'))
            ->set('boxLastWarLimit', $this->getConfig()->get('war_boxLastWarLimit'));
    }
}
