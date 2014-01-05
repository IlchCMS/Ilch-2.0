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
        
        if ($this->getRequest()->getParam('cat')) {
            $parentCategories = $categoryMapper->getCategoriesForParent($this->getRequest()->getParam('cat'));
            $hmenu = array($this->getTranslator()->trans('menuLinks') => array('action' => 'index'));
            foreach($parentCategories as $parent) {
                $hmenu = array_merge($hmenu, array($parent->getName(), array('action' => 'index', 'cat' => $parent->getId())));               
            }
            
            $this->getLayout()->setHmenu($hmenu);
            $links = $linkMapper->getLinks(array('cat_id' => $this->getRequest()->getParam('cat')));
            $categorys = $categoryMapper->getCategories(array('cat_id' => $this->getRequest()->getParam('cat')));
        } else {
            $this->getLayout()->setHmenu(array($this->getTranslator()->trans('menuLinks') => ''));
            $links = $linkMapper->getLinks();
            $categorys = $categoryMapper->getCategories();
        }
        
        $this->getView()->set('links', $links);
        $this->getView()->set('categorys', $categorys);
    }
}


