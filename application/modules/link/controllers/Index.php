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
            $category = $this->checkAccess($category);

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
            $categories = $categoryMapper->getCategoriesByParentId($category->getId());
        } else {
            $links = $linkMapper->getLinksByCatId(0);
            $categories = $categoryMapper->getCategoriesByParentId(0);
        }

        $this->getView()->set('links', $this->checkAccess($links));
        $this->getView()->set('categories', $this->checkAccess($categories));
    }

    public function redirectAction()
    {
        $linkMapper = new LinkMapper();

        $linkModel = ($this->getRequest()->getParam('link_id') && is_numeric($this->getRequest()->getParam('link_id'))) ? $linkMapper->getLinkById($this->getRequest()->getParam('link_id') ?? 0) : 0;
        $linkModel = $this->checkAccess($linkModel);
        if ($linkModel) {
            $linkModel->addHits();
            $linkMapper->save($linkModel);

            header('location: ' . $linkModel->getLink());
            exit;
        }
        $this->redirect(['action' => 'index']);
    }

    private function checkAccess($linkOrCategoryItems)
    {
        if (!$linkOrCategoryItems) {
            // Object is null? Early return.
            return $linkOrCategoryItems;
        }

        if (!($this->getUser() && $this->getUser()->isAdmin())) {
            if (!is_array($linkOrCategoryItems)) {
                // Single link or category.
                if (empty($linkOrCategoryItems->getAccess())) {
                    // Visible for everyone.
                    return $linkOrCategoryItems;
                }

                if (is_in_array(explode(',', $linkOrCategoryItems->getAccess()) ? : [], $this->getUser() && $this->getUser()->getGroups() ?: [3])) {
                    return $linkOrCategoryItems;
                }

                return null;
            }

            // Check which links or categories should be visible for the user or guest.
            $linkOrCategoryItemsVisible = [];
            foreach ($linkOrCategoryItems as $key => $linksOrCategoriesItem) {
                if (!is_array($linksOrCategoriesItem)) {
                    if (empty($linksOrCategoriesItem->getAccess())) {
                        // Visible for everyone.
                        $linkOrCategoryItemsVisible[$key] = $linksOrCategoriesItem;
                        continue;
                    }

                    if (is_in_array(explode(',', $linksOrCategoriesItem->getAccess()) ? : [], $this->getUser() && $this->getUser()->getGroups() ?: [3])) {
                        $linkOrCategoryItemsVisible[$key] = $linksOrCategoriesItem;
                    }
                } else {
                    // Subitems
                    foreach ($linksOrCategoriesItem as $linkOrCategoryItem) {
                        if (empty($linkOrCategoryItem->getAccess())) {
                            // Visible for everyone.
                            $linkOrCategoryItemsVisible[$key][] = $linkOrCategoryItem;
                            continue;
                        }

                        if (is_in_array(explode(',', $linkOrCategoryItem->getAccess()) ? : [], $this->getUser() && $this->getUser()->getGroups() ?: [3])) {
                            $linkOrCategoryItemsVisible[$key][] = $linkOrCategoryItem;
                        }
                    }
                }
            }

            $linkOrCategoryItems = $linkOrCategoryItemsVisible;
        }

        return $linkOrCategoryItems;
    }
}
