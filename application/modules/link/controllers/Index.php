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

        if ($this->getRequest()->getParam('cat_id')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('cat_id'));

            if (empty($category)) {
                $this->redirect()
                    ->withMessage('categoryNotFound', 'warning')
                    ->to(['action' => 'index']);
            }
        }

        if (!empty($category)) {
            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index']);

            if (!empty($parentCategories)) {
                foreach ($parentCategories as $parent) {
                    $this->getLayout()->getHmenu()
                        ->add($parent->getName(), ['action' => 'index', 'cat_id' => $parent->getId()]);
                }
            }

            $this->getLayout()->getHmenu()
                ->add($category->getName(), ['action' => 'index', 'cat_id' => $this->getRequest()->getParam('cat_id')]);

            $links = $linkMapper->getLinks(['cat_id' => $this->getRequest()->getParam('cat_id')]);
            $categorys = $categoryMapper->getCategories(['parent_id' => $this->getRequest()->getParam('cat_id')]);
        } else {
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuLinks'), ['action' => 'index']);

            $links = $linkMapper->getLinks(['cat_id' => 0]);
            $categorys = $categoryMapper->getCategories(['parent_id' => 0]);
        }

        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }

    public function redirectAction()
    {
        $linkMapper = new LinkMapper();

        if (empty($this->getRequest()->getParam('link_id')) || !is_numeric($this->getRequest()->getParam('link_id'))) {
            return;
        }

        $linkModel = $linkMapper->getLinkById($this->getRequest()->getParam('link_id'));
        if (!empty($linkModel)) {
            $linkModel->setHits($linkModel->getHits() + 1);
            $linkMapper->save($linkModel);

            header('location: ' .$linkModel->getLink());
            exit;
        }
    }
}
