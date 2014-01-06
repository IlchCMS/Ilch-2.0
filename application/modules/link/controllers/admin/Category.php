<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Controllers\Admin;

use Link\Controllers\Admin\Base as BaseController;
use Link\Mappers\Category as CategoryMapper;
use Link\Models\Category as CategoryModel;

defined('ACCESS') or die('no direct access');

class Category extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewCategory',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'category', 'action' => 'treat', 'id' => 0))
            )
        );
    }
    
    public function indexAction()
    {
        $categorykMapper = new CategoryMapper();
        $categorys = $categorykMapper->getCategories();
        $this->getView()->set('categorys', $categorys);
    }

    public function deleteAction()
    {
        $categorykMapper = new CategoryMapper();
        $categorykMapper->delete($this->getRequest()->getParam('id'));
        $this->addMessage('saveSuccess');
        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
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

            $model->setName($this->getRequest()->getPost('name'));
            $model->setDesc($this->getRequest()->getPost('desc'));

            $categorykMapper->save($model);
            $this->addMessage('saveSuccess');
            $this->redirect(array('action' => 'index'));
        }
    }
}