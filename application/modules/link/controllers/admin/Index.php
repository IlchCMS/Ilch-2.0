<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Controllers\Admin;

use Link\Mappers\Link as LinkMapper;
use Link\Mappers\Category as CategoryMapper;
use Link\Models\Link as LinkModel;
use Link\Models\Category as CategoryModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuLinks',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewLink',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treatLink', 'catId' => $this->getRequest()->getParam('cat_id')))
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewCategory',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treatCat', 'parentId' => $this->getRequest()->getParam('cat_id')))
            )
        );
    }
    
 

    public function indexAction()
    {
        $linkMapper = new LinkMapper();
        $categoryMapper = new CategoryMapper();
        
        if ($this->getRequest()->getParam('cat_id')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('cat_id'));
            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());
            
            $links = $linkMapper->getLinks(array('cat_id' => $this->getRequest()->getParam('cat_id')));
            $categorys = $categoryMapper->getCategories(array('parent_id' => $this->getRequest()->getParam('cat_id')));
        } else {
            $links = $linkMapper->getLinks(array('cat_id' => 0));
            $categorys = $categoryMapper->getCategories(array('parent_id' => 0));
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
        $categorykMapper = new CategoryMapper();
        $categorykMapper->delete($this->getRequest()->getParam('id'));
        
        $this->addMessage('deleteSuccess');
        
        $this->redirect(array('action' => 'index'));
    }

    public function deleteLinkAction()
    {
        $linkMapper = new LinkMapper();
        $linkMapper->delete($this->getRequest()->getParam('id'));
        
        $this->addMessage('deleteSuccess');
        
        $this->redirect(array('action' => 'index'));
    }

    public function treatLinkAction()
    {
        $linkMapper = new LinkMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('link', $linkMapper->getLinkById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new LinkModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $name = $this->getRequest()->getPost('name');
            $link = trim($this->getRequest()->getPost('link'));
            
            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($link)) {
                $this->addMessage('missingLink', 'danger');
            } else {
                $model->setName($this->getRequest()->getPost('name'));
                $model->setLink($this->getRequest()->getPost('link'));
                $model->setBanner($this->getRequest()->getPost('banner'));
                $model->setDesc($this->getRequest()->getPost('desc'));
                $model->setCatId($this->getRequest()->getParam('catId'));
                $linkMapper->save($model);

                $this->addMessage('saveSuccess');
            
                if ($this->getRequest()->getParam('catId')) {
                    $this->redirect(array('action' => 'index', 'cat_id' => $this->getRequest()->getParam('catId')));
                } else {
                    $this->redirect(array('action' => 'index'));
                }    
            }
        }
    }

    public function treatCatAction()
    {
        $categorykMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('category', $categorykMapper->getCategoryById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new CategoryModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $name = $this->getRequest()->getPost('name');
            
            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } else {
                $model->setName($this->getRequest()->getPost('name'));
                $model->setDesc($this->getRequest()->getPost('desc'));
                $model->setParentID($this->getRequest()->getParam('parentId'));
                $categorykMapper->save($model);

                $this->addMessage('saveSuccess');

                if ($this->getRequest()->getParam('parentId')) {
                    $this->redirect(array('action' => 'index', 'cat_id' => $this->getRequest()->getParam('parentId')));
                } else {
                    $this->redirect(array('action' => 'index'));
                }
            }   
        }
    }
}