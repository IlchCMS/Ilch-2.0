<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Controllers\Admin;

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
                'name' => 'applications',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'applications', 'action' => 'index'])
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
            Validation::setCustomFieldAliases([
                'image_height' => 'imageHeight',
                'image_width' => 'imageWidth'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'image_height' => 'required|numeric|integer|min:1',
                'image_width' => 'required|numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('teams_height', $this->getRequest()->getPost('image_height'));
                $this->getConfig()->set('teams_width', $this->getRequest()->getPost('image_width'));
                $this->getConfig()->set('teams_filetypes', strtolower($this->getRequest()->getPost('image_filetypes')));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('teams_height', $this->getConfig()->get('teams_height'))
            ->set('teams_width', $this->getConfig()->get('teams_width'))
            ->set('teams_filetypes', $this->getConfig()->get('teams_filetypes'));
    }
}
