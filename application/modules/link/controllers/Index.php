<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Controllers;

use Link\Mappers\Link as LinkMapper;
use Link\Mappers\Category as CategoryMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $linkMapper = new LinkMapper();
        $categoryMapper = new CategoryMapper();
        
        if ($this->getRequest()->getParam('cat_id')) {
            $parentCategories = $categoryMapper->getCategoriesForParent($this->getRequest()->getParam('cat_id'));
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLinks'), array('action' => 'index'));

            foreach($parentCategories as $parent) {
                $this->getLayout()->getHmenu()->add($parent->getName(), array('action' => 'index', 'cat_id' => $this->getRequest()->getParam('cat_id')));
            }
            
            $links = $linkMapper->getLinks(array('cat_id' => $this->getRequest()->getParam('cat_id')));
            $categorys = $categoryMapper->getCategories(array('cat_id' => $this->getRequest()->getParam('cat_id')));
        } else {
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLinks'));
            $links = $linkMapper->getLinks();
            $categorys = $categoryMapper->getCategories();
        }
        
        $this->getView()->set('links', $linkMapper->getLinks(array('cat_id' => 0)));
        $this->getView()->set('categorys', $categorys);
    }
}


