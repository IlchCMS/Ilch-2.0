<?php
/**
 * @copyright Ilch 2.0
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
            $parentCategories = $categoryMapper->getCategoriesForParent($category->getParentId());
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'));

            if (!empty($parentCategories)) {
                foreach($parentCategories as $parent) {
                    $this->getLayout()->getHmenu()->add($parent->getName(), array('action' => 'index', 'cat_id' => $parent->getId()));
                }
            }
            
            $this->getLayout()->getHmenu()->add($category->getName(), array('action' => 'index', 'cat_id' => $this->getRequest()->getParam('cat_id')));
            
            $links = $linkMapper->getLinks(array('cat_id' => $this->getRequest()->getParam('cat_id')));
            $categorys = $categoryMapper->getCategories(array('parent_id' => $this->getRequest()->getParam('cat_id')));
        } else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'));
            $links = $linkMapper->getLinks(array('cat_id' => 0));
            $categorys = $categoryMapper->getCategories(array('parent_id' => 0));
        }

        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }
    
    public function redirectAction()
    {
        $linkMapper = new LinkMapper();
        $linkModel = $linkMapper->getLinkById($this->getRequest()->getParam('link_id'));
        $linkModel->setHits($linkModel->getHits() + 1);
        $linkMapper->save($linkModel);
        header("location: ".$linkModel->getLink());
        exit;
    }
}


