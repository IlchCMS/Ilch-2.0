<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Link\Controllers\Admin;

use Modules\Link\Mappers\Link as LinkMapper;
use Modules\Link\Mappers\Category as CategoryMapper;
use Modules\Link\Models\Link as LinkModel;
use Modules\Link\Models\Category as CategoryModel;

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
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewLink',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treatLink', 'catId' => $this->getRequest()->getParam('cat_id')))
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewCategory',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treatCat', 'parentId' => $this->getRequest()->getParam('cat_id')))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'));

        $linkMapper = new LinkMapper();
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_cats')) {
            foreach($this->getRequest()->getPost('check_cats') as $catId) {
                $categoryMapper->delete($catId);
            }
        }

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_links')) {
            foreach($this->getRequest()->getPost('check_links') as $linkId) {
                $linkMapper->delete($linkId);
            }
        }

        if ($this->getRequest()->getParam('cat_id')) {
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
        if($this->getRequest()->isSecure()) {
            $categorykMapper = new CategoryMapper();
            $categorykMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function deleteLinkAction()
    {
        if($this->getRequest()->isSecure()) {
            $linkMapper = new LinkMapper();
            $linkMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function treatLinkAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuActionNewLink'), array('action' => 'treat'));

        $categoryMapper = new CategoryMapper();
        $linkMapper = new LinkMapper();
        $this->getView()->set('cats', $categoryMapper->getCategories());

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
                $model->setCatId($this->getRequest()->getPost('catId'));
                $linkMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function treatCatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuActionNewCategory'), array('action' => 'treat'));

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