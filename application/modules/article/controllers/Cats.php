<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Mappers\Category as CategoryMapper;

defined('ACCESS') or die('no direct access');

class Cats extends \Ilch\Controller\Frontend
{
    public function init()
    {
        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp')) {
            if ($this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
        }

        $this->locale = $locale;
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuCats'), array('action' => 'index'));

        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();

        $articlesCats = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuCats'), array('action' => 'index'))
                ->add($articlesCats->getName(), array('action' => 'show', 'id' => $articlesCats->getId()));

        $this->getView()->set('articles', $articleMapper->getArticlesByCats($this->getRequest()->getParam('id'), $this->locale));
    }
}
