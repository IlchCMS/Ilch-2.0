<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\Faq\Models\Faq as FaqModel;
use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Models\Category as CategoryModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuFaqs',
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
                'name' => 'addFaq',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'addCategory',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treatCat'))
            )
        );
    }

    public function indexAction()
    {
        $faqMapper = new FaqMapper();
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('catId')) {
            $catTitle = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('cat'), array('action' => 'index'))
                    ->add($catTitle->getTitle(), array('action' => 'index', 'catId' => $this->getRequest()->getParam('catId')));

            $faqs = $faqMapper->getFaqs(array('cat_id' => $this->getRequest()->getParam('catId')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));

            $faqs = $faqMapper->getFaqs(array('cat_id' => 0));
        }

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_cats')) {
            foreach($this->getRequest()->getPost('check_cats') as $catId) {
                $categoryMapper->delete($catId);
            }
        }

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_faqs')) {
            foreach($this->getRequest()->getPost('check_faqs') as $faqId) {
                $faqMapper->delete($faqId);
            }
        }

        if ($this->getRequest()->getParam('catId')) {
            $faqs = $faqMapper->getFaqs(array('cat_id' => $this->getRequest()->getParam('catId')));
        } else {
            $faqs = $faqMapper->getFaqs(array('cat_id' => 0));
        }

        $this->getView()->set('faqs', $faqs);
        $this->getView()->set('categorys', $categoryMapper->getCategories());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('faq', $faqMapper->getFaqById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));
        }

        if ($this->getRequest()->isPost()) {
            $model = new FaqModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = $this->getRequest()->getPost('title');
            $text = $this->getRequest()->getPost('text');

            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $model->setTitle($title);
                $model->setText($text);
                $model->setCatId($this->getRequest()->getPost('catId'));
                $faqMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function treatCatAction()
    {
        $categorykMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('cat'), array('action' => 'treatCat'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treatCat'));

            $this->getView()->set('category', $categorykMapper->getCategoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('cat'), array('action' => 'treatCat'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treatCat'));
        }

        if ($this->getRequest()->isPost()) {
            $model = new CategoryModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = $this->getRequest()->getPost('title');
            
            if (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } else {
                $model->setTitle($title);
                $categorykMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }   
        }
    }

    public function delCatAction()
    {
        if($this->getRequest()->isSecure()) {
            $categorykMapper = new CategoryMapper();
            $categorykMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function delFaqAction()
    {
        if($this->getRequest()->isSecure()) {
            $faqMapper = new FaqMapper();
            $faqMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
