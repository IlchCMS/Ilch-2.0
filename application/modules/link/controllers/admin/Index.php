<?php
/**
 * @copyright Ilch 2.0
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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionNewLink',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treatLink', 'catId' => $this->getRequest()->getParam('cat_id')])
                ],
                [
                    'name' => 'menuActionNewCategory',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treatCat', 'parentId' => $this->getRequest()->getParam('cat_id')])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treatLink') {
            $items[0][0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'treatCat') {
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
                ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_cats')) {
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $categoryMapper->delete($catId);
            }
        }

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_links')) {
            foreach ($this->getRequest()->getPost('check_links') as $linkId) {
                $linkMapper->delete($linkId);
            }
        }

        if ($this->getRequest()->getParam('cat_id')) {
            $links = $linkMapper->getLinks(['cat_id' => $this->getRequest()->getParam('cat_id')]);
            $categorys = $categoryMapper->getCategories(['parent_id' => $this->getRequest()->getParam('cat_id')]);
        } else {
            $links = $linkMapper->getLinks(['cat_id' => 0]);
            $categorys = $categoryMapper->getCategories(['parent_id' => 0]);
        }
        
        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }
    
    public function redirectAction()
    {
        $linkMapper = new LinkMapper();
        $linkModel = $linkMapper->getLinkById($this->getRequest()->getParam('link_id'));
        $linkModel->setHits($linkModel->getHits() + 1);
        $linkMapper->save($linkModel);
        header("location: ".$linkModel->getLink());
        exit;
    }

    public function deleteCatAction()
    {
        $linkMapper = new LinkMapper();
        $countLinks = count($linkMapper->getLinks(['cat_id' => $this->getRequest()->getParam('id')]));

        if ($countLinks == 0) {
            if ($this->getRequest()->isSecure()) {
                $categorykMapper = new CategoryMapper();
                $categorykMapper->delete($this->getRequest()->getParam('id'));
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
            $linkMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function treatLinkAction()
    {
        $categoryMapper = new CategoryMapper();
        $linkMapper = new LinkMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
    
            $this->getView()->set('link', $linkMapper->getLinkById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'name' => '',
            'link' => '',
            'banner' => '',
            'desc' => ''
        ];

        $this->getView()->set('cats', $categoryMapper->getCategories());

        if ($this->getRequest()->isPost()) {
            $model = new LinkModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            // Add BASE_URL if banner starts with application to get a complete URL for validation
            $banner = trim($this->getRequest()->getPost('banner'));
            if (!empty($banner)) {
                if (substr($banner, 0, 11) == 'application') {
                    $banner = BASE_URL.'/'.$banner;
                }
            }

            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'link' => trim($this->getRequest()->getPost('link')),
                'banner' => $banner,
                'desc' => $this->getRequest()->getPost('desc')
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'link' => 'required|url',
                'banner' => 'url'
            ]);

            if ($validation->isValid()) {
                $model->setName($this->getRequest()->getPost('name'));
                $model->setLink($this->getRequest()->getPost('link'));
                // Used on purpose instead of $banner to save some bytes in the database
                $model->setBanner($this->getRequest()->getPost('banner'));
                $model->setDesc($this->getRequest()->getPost('desc'));
                $model->setCatId($this->getRequest()->getPost('catId'));
                $linkMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrors());
            $errorFields = $validation->getFieldsWithError();
            $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }

    public function treatCatAction()
    {
        $categorykMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
    
            $this->getView()->set('category', $categorykMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'name' => '',
            'desc' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $model = new CategoryModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'desc' => $this->getRequest()->getPost('desc')
            ];

            $validation = Validation::create($post, [
                'name' => 'required'
            ]);

            if ($validation->isValid()) {
                $model->setName($this->getRequest()->getPost('name'));
                $model->setDesc($this->getRequest()->getPost('desc'));
                if (!empty($this->getRequest()->getParam('parentId'))) {
                    $model->setParentID($this->getRequest()->getParam('parentId'));
                }
                $categorykMapper->save($model);

                $this->addMessage('saveSuccess');
                if ($this->getRequest()->getParam('parentId')) {
                    $this->redirect(['action' => 'index', 'cat_id' => $this->getRequest()->getParam('parentId')]);
                } else {
                    $this->redirect(['action' => 'index']);
                }
            }

            $this->getView()->set('errors', $validation->getErrors($this->getTranslator()));
            $errorFields = $validation->getFieldsWithError();
            $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }
}
