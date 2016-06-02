<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\User\Mappers\User as UserMapper;

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
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index']);

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $articlesCats = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($articlesCats->getName(), ['action' => 'show', 'id' => $articlesCats->getId()]);

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('articles', $articleMapper->getArticlesByCats($this->getRequest()->getParam('id'), $this->locale, $pagination));
        $this->getView()->set('pagination', $pagination);
    }
}
