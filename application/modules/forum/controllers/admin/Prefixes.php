<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Validation;
use Modules\Forum\Mappers\Prefixes as PrefixMapper;
use Modules\Forum\Models\Prefix as PrefixModel;

class Prefixes extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => false,
                'icon' => 'fa-solid fa-table-cells',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuPrefixes',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'prefixes', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'prefixes', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuReports',
                'active' => false,
                'icon' => 'fa-solid fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'forum',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('prefixes'), ['action' => 'index']);

        $prefixMapper = new PrefixMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_forumPrefixes')) {
            foreach ($this->getRequest()->getPost('check_forumPrefixes') as $prefixId) {
                $prefixMapper->deletebyId($prefixId);
            }
        }

        $this->getView()->set('prefixes', $prefixMapper->getPrefixes());
    }

    public function treatAction()
    {
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('prefixes'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('prefixes'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $prefixModel = new PrefixModel();
        $prefixMapper = new PrefixMapper();

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'prefix' => 'required'
            ]);

            if ($validation->isValid()) {
                $prefixModel->setId($this->getRequest()->getParam('id'));
                $prefixModel->setPrefix($this->getRequest()->getPost('prefix'));
                $prefixMapper->save($prefixModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        $this->getView()->set('prefix', ($this->getRequest()->getParam('id')) ? $prefixMapper->getPrefixById($this->getRequest()->getParam('id')) : null);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $prefixMapper = new PrefixMapper();

            $prefixMapper->deleteById($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
