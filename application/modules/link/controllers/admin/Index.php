<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Controllers\Admin;

use Modules\Link\Mappers\Link as LinkMapper;
use Modules\Link\Models\Link as LinkModel;
use Modules\Link\Mappers\Category as CategoryMapper;
use Modules\Link\Models\Category as CategoryModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionNewLink',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(array_merge(
                        ['controller' => 'index', 'action' => 'treatLink'],
                        $this->getRequest()->getParam('cat_id') ? [ 'catId' => $this->getRequest()->getParam('cat_id')] : []
                    ))
                ],
                [
                    'name' => 'menuActionNewCategory',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(array_merge(
                        ['controller' => 'index', 'action' => 'treatCat'],
                        $this->getRequest()->getParam('cat_id') ? ['parentId' => $this->getRequest()->getParam('cat_id')] : [],
                    ))
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treatLink') {
            $items[0][0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'treatCat') {
            $items[0][1]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuLinks',
            $items
        );
    }

    public function indexAction()
    {
        $linkMapper = new LinkMapper();
        $categoryMapper = new CategoryMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index']);

        if ($this->getRequest()->getParam('cat_id')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('cat_id'));

            if (!$category) {
                $this->redirect()
                    ->withMessage('categoryNotFound', 'warning')
                    ->to(['action' => 'index']);
            }

            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());

            foreach ($parentCategories ?? [] as $parent) {
                $this->getLayout()->getAdminHmenu()
                    ->add($parent->getName(), ['action' => 'index', 'cat_id' => $parent->getId()]);
            }

            $this->getLayout()->getAdminHmenu()
                ->add($category->getName(), ['action' => 'index', 'cat_id' => $category->getId()]);

            $links = $linkMapper->getLinksByCatId($this->getRequest()->getParam('cat_id'));
            $categorys = $categoryMapper->getCategorysByParentId($this->getRequest()->getParam('cat_id'));
        } else {
            $links = $linkMapper->getLinksByCatId(0);
            $categorys = $categoryMapper->getCategorysByParentId(0);
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_cats')) {
                foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                    $categoryMapper->delete($catId);
                }
                $this->addMessage('deleteSuccess');
            } elseif ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_links')) {
                foreach ($this->getRequest()->getPost('check_links') as $linkId) {
                    $linkMapper->delete($linkId);
                }
                $this->addMessage('deleteSuccess');
            } else {
                if ($this->getRequest()->getPost('hiddenMenu')) {
                    $positions = explode(',', $this->getRequest()->getPost('hiddenMenu'));

                    foreach ($positions as $x => $xValue) {
                        $linkMapper->updatePositionById($xValue, $x);
                    }
                }
                if ($this->getRequest()->getPost('hiddenMenuCat')) {
                    $positionsCat = explode(',', $this->getRequest()->getPost('hiddenMenuCat'));

                    foreach ($positionsCat as $x => $xValue) {
                        $categoryMapper->updatePositionById($xValue, $x);
                    }
                }
                $this->addMessage('saveSuccess');
            }
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }

    public function redirectAction()
    {
        $linkMapper = new LinkMapper();

        $linkModel = $linkMapper->getLinkById($this->getRequest()->getParam('link_id', 0));
        if ($linkModel) {
            $linkModel->addHits();
            $linkMapper->save($linkModel);

            header('location: ' . $linkModel->getLink());
            exit;
        }
        $this->redirect(['action' => 'index']);
    }

    public function deleteCatAction()
    {
        $linkMapper = new LinkMapper();
        $countLinks = count($linkMapper->getLinksByCatId($this->getRequest()->getParam('id', -1)) ?? []);

        if ($countLinks == 0) {
            if ($this->getRequest()->isSecure()) {
                $categorykMapper = new CategoryMapper();
                $categorykMapper->delete($this->getRequest()->getParam('id', 0));
                $this->addMessage('deleteSuccess');
            }
        } else {
            $this->addMessage('deleteFailed', 'danger');
        }

        $this->redirect(['action' => 'index']);
    }

    public function deleteLinkAction()
    {
        if ($this->getRequest()->isSecure()) {
            $linkMapper = new LinkMapper();
            $linkMapper->delete($this->getRequest()->getParam('id', 0));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function treatLinkAction()
    {
        $categoryMapper = new CategoryMapper();
        $linkMapper = new LinkMapper();

        $model = new LinkModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuActionEditLink'), ['action' => 'treat']);

            $model = $linkMapper->getLinkById($this->getRequest()->getParam('id'));

            if (!$model) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuActionNewLink'), ['action' => 'treat']);
        }
        $this->getView()->set('link', $model);

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL if banner starts with application to get a complete URL for validation
            $banner = $this->getRequest()->getPost('banner');
            if (!empty($banner) && strncmp($banner, 'application', 11) === 0) {
                $banner = $this->getView()->getBaseUrl($banner);
            }

            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'link' => $this->getRequest()->getPost('link'),
                'banner' => $banner,
                'desc' => $this->getRequest()->getPost('desc'),
                'catId' => $this->getRequest()->getPost('catId'),
            ];

            Validation::setCustomFieldAliases([
                'catId' => 'category',
            ]);

            $validation = Validation::create($post, array_merge([
                'name' => 'required',
                'link' => 'required|url',
                'banner' => 'url',
            ], $this->getRequest()->getPost('catId') ? ['catId' => 'numeric|integer|min:0|exists:' . $categoryMapper->tablename . ',id'] : ['catId' => 'numeric|integer|min:0']));

            $post['banner'] = $this->getRequest()->getPost('banner');

            if ($validation->isValid()) {
                $model->setName($post['name'])
                    ->setLink($post['link'])
                    ->setBanner($post['banner'])
                    ->setDesc($post['desc'])
                    ->setCatId($post['catId']);
                $linkMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treatLink'], ($model->getId() ? ['id' => $model->getId()] : [])));
            }
        }

        $this->getView()->set('cats', $categoryMapper->getCategories() ?? []);
    }

    public function treatCatAction()
    {
        $categorykMapper = new CategoryMapper();

        $model = new CategoryModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuActionEditCategory'), ['action' => 'treat']);

            $model = $categorykMapper->getCategoryById($this->getRequest()->getParam('id'));

            if (!$model) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuActionNewCategory'), ['action' => 'treat']);
        }
        $this->getView()->set('category', $model);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required'
            ]);

            if ($validation->isValid()) {
                if ($this->getRequest()->getParam('parentId')) {
                    $model->setParentID($this->getRequest()->getParam('parentId'));
                }
                $model->setName($this->getRequest()->getPost('name'))
                    ->setDesc($this->getRequest()->getPost('desc'));
                $categorykMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(array_merge(['action' => 'index'], ($model->getParentId() ? ['cat_id' => $model->getParentId()] : [])));
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(array_merge(['action' => 'treatCat'], ($model->getId() ? ['id' => $model->getId()] : [])));
            }
        }
    }
}
