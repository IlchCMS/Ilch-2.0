<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Controllers;

use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Mappers\Faq as FaqMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));

        $this->getView()->set('faqs', $faqMapper->getFaqs(array('cat_id' => 0)));
        $this->getView()->set('categorys', $categoryMapper->getCategories(array('parent_id' => 0)));
    }

    public function showCatAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));
            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());

            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));

            if (!empty($parentCategories)) {
                foreach($parentCategories as $parent) {
                    $this->getLayout()->getHmenu()->add($parent->getTitle(), array('action' => 'showCat', 'catId' => $parent->getId()));
                }
            }

            $this->getLayout()->getHmenu()->add($category->getTitle(), array('action' => 'showCat', 'catId' => $this->getRequest()->getParam('catId')));

            $faqs = $faqMapper->getFaqs(array('cat_id' => $this->getRequest()->getParam('catId')));
            $categorys = $categoryMapper->getCategories(array('parent_id' => $this->getRequest()->getParam('catId')));
        } else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));

            $faqs = $faqMapper->getFaqs();
            $categorys = $categoryMapper->getCategories(array('parent_id' => $category->getId()));
        }

        $this->getView()->set('faqs', $faqs);
        $this->getView()->set('categorys', $categorys);
    }
}
