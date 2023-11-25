<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Controllers;

use Modules\Link\Mappers\Link as LinkMapper;
use Modules\Link\Mappers\Category as CategoryMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $linkMapper = new LinkMapper();
        $categoryMapper = new CategoryMapper();

        $category = null;
        if ($this->getRequest()->getParam('cat_id')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('cat_id'));

            if (!$category) {
                $this->redirect()
                    ->withMessage('categoryNotFound', 'warning')
                    ->to(['action' => 'index']);
            }
        }

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index']);

        if ($category) {
            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());

            foreach ($parentCategories ?? [] as $parent) {
                $this->getLayout()->getHmenu()
                    ->add($parent->getName(), ['action' => 'index', 'cat_id' => $parent->getId()]);
            }

            $this->getLayout()->getHmenu()
                ->add($category->getName(), ['action' => 'index', 'cat_id' => $category->getId()]);

            $links = $linkMapper->getLinksByCatId($category->getId());
            $categorys = $categoryMapper->getCategorysByParentId($category->getId());
        } else {
            $links = $linkMapper->getLinksByCatId(0);
            $categorys = $categoryMapper->getCategorysByParentId(0);
        }

        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }

    public function redirectAction()
    {
        $linkMapper = new LinkMapper();

        $linkModel = $linkMapper->getLinkById($this->getRequest()->getParam('link_id') ?? 0);
        if ($linkModel) {
            $linkModel->addHits();
            $linkMapper->save($linkModel);

            header('location: ' . $linkModel->getLink());
            exit;
        }
        $this->redirect(['action' => 'index']);
    }
}
