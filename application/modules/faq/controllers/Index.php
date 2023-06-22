<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Controllers;

use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();
        $userMapper = new UserMapper();

        $readAccess = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }
        }

        if ($this->getRequest()->isPost()) {
            $searchTerm = trim($this->getRequest()->getPost('search'));
            $this->getView()->set('searchExecuted', true);

            if (!empty($searchTerm)) {
                $searchResult = $faqMapper->search($searchTerm, $readAccess);
                $this->getView()->set('searchresult', $searchResult);
            }
        }

        $sortCategoriesAlphabetically = ($this->getConfig()->get('faq_sortCategoriesAlphabetically') === '1');
        $sortQuestionsAlphabetically = ($this->getConfig()->get('faq_sortQuestionsAlphabetically') === '1');

        if ($sortCategoriesAlphabetically) {
            $categories = $categoryMapper->getCategories([], ['title' => 'ASC'], $readAccess);
        } else {
            $categories = $categoryMapper->getCategories([], ['id' => 'ASC'], $readAccess);
        }
        if (!$categories) {
            $categories = [];
        }

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'), $readAccess);

            if (!$category) {
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);

            if ($sortQuestionsAlphabetically) {
                $faqs = $faqMapper->getFaqs(['f.cat_id' => $this->getRequest()->getParam('catId')], ['f.question' => 'ASC'], $readAccess);
            } else {
                $faqs = $faqMapper->getFaqs(['f.cat_id' => $this->getRequest()->getParam('catId')], ['f.id' => 'ASC'], $readAccess);
            }
        } else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

            if ($sortQuestionsAlphabetically) {
                $faqs = $faqMapper->getFaqs([], ['f.question' => 'ASC'], $readAccess);
            } else {
                $faqs = $faqMapper->getFaqs([], ['f.id' => 'ASC'], $readAccess);
            }
        }

        $this->getView()->set('faqMapper', $faqMapper);
        $this->getView()->set('faqs', $faqs);
        $this->getView()->set('categories', $categories);
        $this->getView()->set('readAccess', $readAccess);
    }

    public function showAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();
        $userMapper = new UserMapper();

        $faq = $faqMapper->getFaqById($this->getRequest()->getParam('id'));

        if (!$faq) {
            $this->redirect(['action' => 'index']);
        }

        $readAccess = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }
        }

        $category = $categoryMapper->getCategoryById($faq->getCatId(), $readAccess);

        if (!$category) {
            $this->redirect(['action' => 'index']);
        }

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
            ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()])
            ->add($faq->getQuestion(), ['action' => 'show', 'id' => $faq->getId()]);

        $this->getView()->set('faq', $faq);
    }
}
