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

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        if ($this->getRequest()->isPost()) {
            $searchTerm = trim($this->getRequest()->getPost('search'));
            $this->getView()->set('searchExecuted', true);

            if (!empty($searchTerm)) {
                $tmpSearchResult = $faqMapper->search(['question LIKE' => '%'.$searchTerm.'%']);
                $categoriesReadAccessCache = [];

                foreach ($tmpSearchResult as $result) {
                    if (!isset($categoriesReadAccessCache[$result->getCatId()])) {
                        $category = $categoryMapper->getCategoryById($result->getCatId());
                        if ($category !== null) {
                            $categoriesReadAccessCache[$result->getCatId()] = $category->getReadAccess();
                        } else {
                            $categoriesReadAccessCache[$result->getCatId()] = '';
                        }
                    }

                    if (!($adminAccess == true || is_in_array($readAccess, explode(',', $categoriesReadAccessCache[$result->getCatId()])))) {
                        continue;
                    }

                    $searchResult[] = $result;
                }

                $this->getView()->set('searchresult', $searchResult);
            }
        }

        $sortCategoriesAlphabetically = ($this->getConfig()->get('faq_sortCategoriesAlphabetically') === '1');
        $sortQuestionsAlphabetically = ($this->getConfig()->get('faq_sortQuestionsAlphabetically') === '1');

        if ($sortCategoriesAlphabetically) {
            $categories = $categoryMapper->getCategories([], ['title' => 'ASC']);
        } else {
            $categories = $categoryMapper->getCategories();
        }

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

            if (!$category) {
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

            if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->read_access))) {
                $this->getLayout()->getHmenu()
                    ->add($category->title, ['action' => 'index', 'catId' => $category->id]);
            } else {
                $this->redirect(['action' => 'index']);
            }

            if ($sortQuestionsAlphabetically) {
                $faqs = $faqMapper->getFaqs(['cat_id' => $this->getRequest()->getParam('catId')], ['question' => 'ASC']);
            } else {
                $faqs = $faqMapper->getFaqs(['cat_id' => $this->getRequest()->getParam('catId')]);
            }
        } else {
            $firstAllowedCategory = null;

            foreach ($categories as $category) {
                if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->reas_access))) {
                    $firstAllowedCategory = $category;
                    break;
                }
            }

            if ($firstAllowedCategory !== null) {
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($firstAllowedCategory->title, ['action' => 'index', 'catId' => $firstAllowedCategory->id]);

                if ($sortQuestionsAlphabetically) {
                    $faqs = $faqMapper->getFaqs(['cat_id' => $firstAllowedCategory->id], ['question' => 'ASC']);
                } else {
                    $faqs = $faqMapper->getFaqs(['cat_id' => $firstAllowedCategory->id]);
                }

                $this->getView()->set('firstCatId', $firstAllowedCategory->id);
            } else {
                $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

                if ($sortQuestionsAlphabetically) {
                    $faqs = $faqMapper->getFaqs([], ['question' => 'ASC']);
                } else {
                    $faqs = $faqMapper->getFaqs();
                }
            }
        }

        $this->getView()->set('faqMapper', $faqMapper);
        $this->getView()->set('faqs', $faqs);
        $this->getView()->set('categories', $categories);
        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('adminAccess', $adminAccess);
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

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $category = $categoryMapper->getCategoryById($faq->catId);

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

        if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->read_access))) {
            $this->getLayout()->getHmenu()
                ->add($category->title, ['action' => 'index', 'catId' => $category->id])
                ->add($faq->question, ['action' => 'show', 'id' => $faq->id]);
        } else {
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('faq', $faq);
    }
}
