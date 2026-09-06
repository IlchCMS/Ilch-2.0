<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Controllers\Admin;

use Modules\Kvticket\Mappers\Category as CategoryMapper;
use Modules\Kvticket\Models\Category as CategoryModel;
use Ilch\Validation;

class Cat extends \Ilch\Controller\Admin
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
                
                'name' => 'cat',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cat', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'cat', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuTickets',
            $items
        );
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTickets'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_tickets')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_tickets') as $ticketId) {
                    $categoryMapper->delete($ticketId);
                }
            }
        }

        $this->getView()->set('cats', $categoryMapper->getCategorys());
    }

    public function treatAction() 
    {
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTickets'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('cat', $categoryMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuTickets'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required'
            ]);

            if ($validation->isValid()) {
                $categoryModel = new CategoryModel();
                if ($this->getRequest()->getParam('id')) {
                    $categoryModel->setId($this->getRequest()->getParam('id'));
                }
                $categoryModel->setTitle($this->getRequest()->getPost('title'));
                $categoryMapper->save($categoryModel);

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
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $categoryMapper = new categoryMapper();
            $categoryMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
