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
    /**
     * @var string
     */
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

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuCats'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index']);

        $this->getView()->set('articleMapper', $articleMapper)
            ->set('cats', $categoryMapper->getCategories());
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $id = $this->getRequest()->getParam('id');
        if (empty($id) || !is_numeric($id)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Category', 'errorText' => 'notFound']);
            return;
        }

        $articlesCats = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));
        if (empty($articlesCats)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Category', 'errorText' => 'notFound']);
            return;
        }

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuCats'))
            ->add($articlesCats->getName());
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
            ->add($articlesCats->getName(), ['action' => 'show', 'id' => $articlesCats->getId()]);

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
            ->set('article_articleRating', \Ilch\Registry::get('config')->get('article_articleRating'))
            ->set('articles', $articleMapper->getArticlesByCats($this->getRequest()->getParam('id'), $this->locale, $pagination))
            ->set('readAccess', $readAccess)
            ->set('pagination', $pagination);
    }

    public function voteAction()
    {
        $articleMapper = new ArticleMapper();
        $articleMapper->saveVotes($this->getRequest()->getParam('id'), $this->getUser()->getId());

        $this->redirect(['action' => $this->getRequest()->getParam('from'), 'id' => $this->getRequest()->getParam('catId')]);
    }
}
