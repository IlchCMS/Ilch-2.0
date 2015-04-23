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

        $this->getView()->set('categorys', $categoryMapper->getCategories());
        $this->getView()->set('faqs', $faqMapper->getFaqs(array('cat_id' => '0')));
    }

    public function showCatAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();
        
        $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                                      ->add($category->getTitle(), array('action' => 'index', 'catId' => $this->getRequest()->getParam('catId')));

        $this->getView()->set('categoryTitle', $category->getTitle());
        $this->getView()->set('faqs', $faqMapper->getFaqs(array('cat_id' => $this->getRequest()->getParam('catId'))));
    }
}
