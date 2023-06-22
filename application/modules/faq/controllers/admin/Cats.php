<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

use Ilch\Validation;
use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Models\Category as CategoryModel;
use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\User\Mappers\Group as GroupMapper;

class Cats extends \Ilch\Controller\Admin
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
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuFaqs',
            $items
        );
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $categoryMapper->delete($catId);
            }
            $this->addMessage('deleteSuccess');

            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('faqMapper', $faqMapper);
        $this->getView()->set('cats', $categoryMapper->getEntriesBy());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $groupMapper = new GroupMapper();

        $model = new CategoryModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $model = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('cat', $model);

        if ($this->getRequest()->isPost()) {
            $_POST['title'] = trim($this->getRequest()->getPost('title'));

            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required|unique:' . $categoryMapper->tablename . ',title,' . $model->getId(),
                'groups' => 'required',
            ]);

            if ($validation->isValid()) {
                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    if (in_array('all', $this->getRequest()->getPost('groups'))) {
                        $groups = 'all';
                    } else {
                        $groups = implode(',', $this->getRequest()->getPost('groups'));
                    }
                }

                $model->setTitle($this->getRequest()->getPost('title'))
                    ->setReadAccess($groups);
                $categoryMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($this->getRequest()->getPost())
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treat'], ($model->getId() ? ['id' => $model->getId()] : [])));
            }
        }

        if ($model->getId()) {
            $groups = explode(',', $model->getReadAccess());
        } else {
            $groups = [2, 3];
        }

        $this->getView()->set('groups', $groups);
        $this->getView()->set('userGroupList', $groupMapper->getGroupList());
    }

    public function delCatAction()
    {
        if ($this->getRequest()->isSecure()) {
            $faqMapper = new FaqMapper();
            $countFaqs = count($faqMapper->getFaqsByCatId($this->getRequest()->getParam('id')) ?? []);

            if ($countFaqs == 0) {
                $categoryMapper = new CategoryMapper();
                $categoryMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('deleteSuccess');
            } else {
                $this->addMessage('deleteFailed', 'danger');
            }
        }

        $this->redirect(['action' => 'index']);
    }
}
