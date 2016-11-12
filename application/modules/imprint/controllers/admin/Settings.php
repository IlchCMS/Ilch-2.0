<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Controllers\Admin;

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
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuImprint',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuImprint'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'imprintStyle' => 'site'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'imprintStyle'      => 'required|numeric|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('imprint_style', $this->getRequest()->getPost('imprintStyle'));
                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('imprintStyle', $this->getConfig()->get('imprint_style'));
    }
}
