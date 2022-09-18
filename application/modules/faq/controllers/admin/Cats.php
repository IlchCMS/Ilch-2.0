<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

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
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
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
        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function treatAction() 
    {
        $categoryMapper = new CategoryMapper();
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
        
            $this->getView()->set('cat', $categoryMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {


            if ($this->getRequest()->getParam('id')) {
                $model = new CategoryModel($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));

            $groups = '';
            if (!empty($this->getRequest()->getPost('groups'))) {
                $groups = implode(',', $this->getRequest()->getPost('groups'));
            }

            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } else {
                $model = new CategoryModel(['title' => $title,'read_access' => $groups]);

                $categoryMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(['action' => 'index']);
            }
        }

        if ($this->getRequest()->getParam('id')) {
            $groups = explode(',', $categoryMapper->getCategoryById($this->getRequest()->getParam('id'))->getReadAccess());
        } else {
            $groups = [1,2,3];
        }

        $this->getView()->set('groups', $groups);
        $this->getView()->set('userGroupList', $groupMapper->getGroupList());
    }

    public function delCatAction()
    {
        $faqMapper = new FaqMapper();
        $countFaqs = count($faqMapper->getFaqs(['cat_id' => $this->getRequest()->getParam('id')]));

        if ($countFaqs == 0) {
            if ($this->getRequest()->isSecure()) {
                $categoryMapper = new CategoryMapper();
                $categoryMapper->delete($this->getRequest()->getParam('id'));

                $this->addMessage('deleteSuccess');
            }
        } else {
            $this->addMessage('deleteFailed', 'danger'); 
        }

        $this->redirect(['action' => 'index']);
    }
}
