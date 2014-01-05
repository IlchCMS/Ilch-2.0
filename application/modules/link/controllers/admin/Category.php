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

    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['category'])) {
            $categoryData = $postData['category'];

            $categoryMapper = new CategoryMapper();
            $category = $categoryMapper->loadFromArray($categoryData);
            $categoryId = $categoryMapper->save($category);

            if (!empty($categoryId) && empty($categoryData['id'])) {
                $this->addMessage('newCategoryMsg');
            }

            $this->redirect(array('action' => 'treat', 'id' => $categoryId));
        }
    }

    public function deleteAction()
    {
        $categoryMapper = new CategoryMapper();
        $categoryId = $this->getRequest()->getParam('id');

        if ($categoryId) {
            $deletecategory = $categoryMapper->getCategoryById($categoryId);
            $usersForCategory = $categoryMapper->getUsersForCategory($categoryId);

            if ($categoryId == 1) {
                $this->addMessage('delAdminCategory', 'warning');
            } else {
                if ($categoryMapper->delete($categoryId)) {
                    $this->addMessage('delCategoryMsg');
                }
            }
        }

        $this->redirect(array('action' => 'index'));
    }
}