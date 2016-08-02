<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
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
            $this->getConfig()->set('war_warsPerPage', $this->getRequest()->getPost('warsPerPage'));
            $this->getConfig()->set('war_enemiesPerPage', $this->getRequest()->getPost('enemiesPerPage'));
            $this->getConfig()->set('war_groupsPerPage', $this->getRequest()->getPost('groupsPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('warsPerPage', $this->getConfig()->get('war_warsPerPage'));
        $this->getView()->set('enemiesPerPage', $this->getConfig()->get('war_enemiesPerPage'));
        $this->getView()->set('groupsPerPage', $this->getConfig()->get('war_groupsPerPage'));
    }
}
