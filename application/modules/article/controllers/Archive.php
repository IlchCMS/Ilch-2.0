<?php
/**
 * @copyright Ilch 2
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

        if ((bool)$this->getConfig()->get('multilingual_acp') && $this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
            $locale = $this->getTranslator()->getLocale();
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

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('categoryMapper', $categoryMapper)
            ->set('commentMapper', $commentMapper)
            ->set('userMapper', $userMapper)
            ->set('article_articleRating', \Ilch\Registry::get('config')->get('article_articleRating'))
            ->set('articles', $articleMapper->getArticlesByAccess($readAccess, $this->locale, $pagination))
            ->set('pagination', $pagination);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();
        $datestring = $this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01';
        $articles = null;
        $date = null;

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuArchives'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuArchives'), ['action' => 'index']);

        if (validateDate($datestring, 'Y-m-d')) {
            $date = new \Ilch\Date($datestring);

            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans($date->format('F', true)).$date->format(' Y', true));
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans($date->format('F', true)).$date->format(' Y', true), ['action' => 'show', 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')]);

            $pagination->setRowsPerPage(!$this->getConfig()->get('article_articlesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('article_articlesPerPage'));
            $pagination->setPage($this->getRequest()->getParam('page'));

            $user = null;
            if ($this->getUser()) {
                $user = $userMapper->getUserById($this->getUser()->getId());
            }

            $readAccess = [3];
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }

            $articles = $articleMapper->getArticlesByDateAccess($date, $readAccess, $pagination, $this->locale);

            $this->getView()->set('pagination', $pagination);
        }

        $this->getView()->set('categoryMapper', $categoryMapper)
            ->set('commentMapper', $commentMapper)
            ->set('article_articleRating', \Ilch\Registry::get('config')->get('article_articleRating'))
            ->set('articles', $articles)
            ->set('date', $date);
    }

    public function voteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $from = $this->getRequest()->getParam('from');

        $articleMapper = new ArticleMapper();
        $articleMapper->saveVotes($id, $this->getUser()->getId());

        if ($from === 'show') {
            $this->redirect(['action' => $from, 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')]);
        } else {
            $this->redirect(['action' => $from, 'id' => $id]);
        }
    }
}
