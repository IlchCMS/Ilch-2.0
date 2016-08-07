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

            if (!$category) {
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);

            $faqs = $faqMapper->getFaqs(['cat_id' => $this->getRequest()->getParam('catId')]);
        } else {
            $catId = $categoryMapper->getCategoryMinId();

            if ($catId != '') {
                $category = $categoryMapper->getCategoryById($catId->getId());

                $this->getLayout()->getHmenu()
                        ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                        ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);

                $faqs = $faqMapper->getFaqs(['cat_id' => $catId->getId()]);

                $this->getView()->set('firstCatId', $catId->getId());
            } else {
                $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

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

        if (!$faq) {
            $this->redirect(['action' => 'index']);
        }

        $category = $categoryMapper->getCategoryById($faq->getCatId());

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()])
                ->add($faq->getQuestion(), ['action' => 'show', 'id' => $faq->getId()]);

        $this->getView()->set('faq', $faq);
    }
}
