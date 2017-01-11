<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Controllers\Admin;

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
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuTeams',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('teams_height', $this->getRequest()->getPost('image_height'));
            $this->getConfig()->set('teams_width', $this->getRequest()->getPost('image_width'));
            $this->getConfig()->set('teams_filetypes', strtolower($this->getRequest()->getPost('image_filetypes')));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('teams_height', $this->getConfig()->get('teams_height'));
        $this->getView()->set('teams_width', $this->getConfig()->get('teams_width'));
        $this->getView()->set('teams_filetypes', $this->getConfig()->get('teams_filetypes'));
    }
}
