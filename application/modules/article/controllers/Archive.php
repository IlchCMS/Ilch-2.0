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

class Archive extends \Ilch\Controller\Frontend
{
    /** @var string */
    private $locale;
    
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
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuArchives'), ['action' => 'index']);

        $pagination->setRowsPerPage(empty($this->getConfig()->get('article_articlesPerPage')) ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('article_articlesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('articles', $articleMapper->getArticles($this->locale, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();

        $date = new \Ilch\Date($this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01');
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuArchives'), ['action' => 'index'])
                ->add($date->format('F Y', true), ['action' => 'show', 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')]);

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('articles', $articleMapper->getArticlesByDate($date));
    }
}
