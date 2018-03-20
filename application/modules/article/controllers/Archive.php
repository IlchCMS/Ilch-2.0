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

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuArchives'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuArchives'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('article_articlesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('article_articlesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }
        $user = $userMapper->getUserById($userId);

        $ids = [3];
        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $us) {
                $ids[] = $us->getId();
            }
        }
        $readAccess = explode(',',implode(',', $ids));

        $this->getView()->set('categoryMapper', $categoryMapper)
            ->set('commentMapper', $commentMapper)
            ->set('userMapper', $userMapper)
            ->set('article_articleRating', \Ilch\Registry::get('config')->get('article_articleRating'))
            ->set('articles', $articleMapper->getArticles($this->locale, $pagination))
            ->set('readAccess', $readAccess)
            ->set('pagination', $pagination);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $date = new \Ilch\Date($this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01');

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuArchives'))
            ->add($date->format('F Y', true));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuArchives'), ['action' => 'index'])
            ->add($this->getTranslator()->trans($date->format('F', true)).$date->format(' Y', true), ['action' => 'show', 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')]);

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }
        $user = $userMapper->getUserById($userId);

        $ids = [3];
        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $us) {
                $ids[] = $us->getId();
            }
        }
        $readAccess = explode(',',implode(',', $ids));

        $this->getView()->set('categoryMapper', $categoryMapper)
            ->set('commentMapper', $commentMapper)
            ->set('userMapper', $userMapper)
            ->set('article_articleRating', \Ilch\Registry::get('config')->get('article_articleRating'))
            ->set('articles', $articleMapper->getArticlesByDate($date, $pagination))
            ->set('readAccess', $readAccess)
            ->set('pagination', $pagination);
    }

    public function voteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $from = $this->getRequest()->getParam('from');

        $articleMapper = new ArticleMapper();
        $articleMapper->saveVotes($id, $this->getUser()->getId());

        if ($from == 'show') {
            $this->redirect(['action' => $from, 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')]);
        } else {
            $this->redirect(['action' => $from, 'id' => $id]);
        }
    }
}
