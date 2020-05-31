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



        if ($this->getRequest()->isPost()){
          $suchbegriff = trim ($this->getRequest()->getPost('suche'));

          $result = $faqMapper->suche(['question LIKE' => '%'.$suchbegriff.'%']);

          $this->getView()->set('suchergebnis', $result);


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

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
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

            if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                $this->getLayout()->getHmenu()
                    ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);
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
                if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                    $firstAllowedCategory = $category;
                    break;
                }
            }

            if ($firstAllowedCategory !== null) {
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($firstAllowedCategory->getTitle(), ['action' => 'index', 'catId' => $firstAllowedCategory->getId()]);

                if ($sortQuestionsAlphabetically) {
                    $faqs = $faqMapper->getFaqs(['cat_id' => $firstAllowedCategory->getId()], ['question' => 'ASC']);
                } else {
                    $faqs = $faqMapper->getFaqs(['cat_id' => $firstAllowedCategory->getId()]);
                }

                $this->getView()->set('firstCatId', $firstAllowedCategory->getId());
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

        $category = $categoryMapper->getCategoryById($faq->getCatId());

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

        if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
            $this->getLayout()->getHmenu()
                ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()])
                ->add($faq->getQuestion(), ['action' => 'show', 'id' => $faq->getId()]);
        } else {
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('faq', $faq);
    }
}
