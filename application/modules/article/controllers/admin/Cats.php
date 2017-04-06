<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Article\Models\Category as CategoryModel;
use Modules\Article\Mappers\Article as ArticleMapper;
use Ilch\Validation;

class Cats extends \Ilch\Controller\Admin
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
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'cats' AND $this->getRequest()->getActionName() == 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuArticle',
            $items
        );
    }

    public function indexAction() 
    {
        $categoryMapper = new CategoryMapper();
        
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveCats')) {
            foreach ($this->getRequest()->getPost('items') as $i => $catId) {
                $categoryMapper->sort($catId, $i);
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }
        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $categoryMapper->delete($catId);
            }

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function treatAction() 
    {
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('cat', $categoryMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name'  => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new CategoryModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setName($this->getRequest()->getPost('name'));
                $categoryMapper->save($model);

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

    public function delCatAction()
    {
        if ($this->getRequest()->isSecure()) {
            $articleMapper = new ArticleMapper();

            if ($articleMapper->getArticlesByCats($this->getRequest()->getParam('id')) == '') {
                $categoryMapper = new CategoryMapper();
                $categoryMapper->delete($this->getRequest()->getParam('id'));

                $this->redirect()
                    ->withMessage('deleteSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->redirect()
                    ->withMessage('categoryInUse', 'danger')
                    ->to(['action' => 'index']);
            }
        }
    }
}
