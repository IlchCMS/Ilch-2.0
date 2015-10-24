<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Controllers;

use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Mappers\Faq as FaqMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($category->getTitle(), array('action' => 'index', 'catId' => $category->getId()));

            $faqs = $faqMapper->getFaqs(array('cat_id' => $this->getRequest()->getParam('catId')));
        } else {
            $catId = $categoryMapper->getCategoryMinId();

            if ($catId != '') {
                $category = $categoryMapper->getCategoryById($catId->getId());

                $this->getLayout()->getHmenu()
                        ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                        ->add($category->getTitle(), array('action' => 'index', 'catId' => $category->getId()));

                $faqs = $faqMapper->getFaqs(array('cat_id' => $catId->getId()));

                $this->getView()->set('firstCatId', $catId->getId());
            } else {
                $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));

                $faqs = $faqMapper->getFaqs();
            }
        }

        $this->getView()->set('faqs', $faqs);
        $this->getView()->set('categorys', $categoryMapper->getCategories());
    }

    public function showAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();
        
        $faq = $faqMapper->getFaqById($this->getRequest()->getParam('id'));
        $category = $categoryMapper->getCategoryById($faq->getCatId());

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                ->add($category->getTitle(), array('action' => 'index', 'catId' => $category->getId()))
                ->add($faq->getQuestion(), array('action' => 'show', 'id' => $faq->getId()));

        $this->getView()->set('faq', $faqMapper->getFaqById($this->getRequest()->getParam('id')));
    }
}
