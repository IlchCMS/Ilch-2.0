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

class Keywords extends \Ilch\Controller\Frontend
{
    /**
     * @var string
     */
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

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuKeywords'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuKeywords'), ['action' => 'index']);

        $keywordsList = [];
        foreach ($articleMapper->getKeywordsList() as $keywords) {
            $keywordsList[] = $keywords->getKeywords();
        }

        $keywordsListString = implode(', ', $keywordsList);
        $keywordsListArray = explode(", ", $keywordsListString);
        $keywordsList = array_count_values($keywordsListArray);

        $this->getView()->set('articleMapper', $articleMapper)
            ->set('keywords', $keywordsList);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $keyword = $this->getRequest()->getParam('keyword');

        if (!empty($keyword) && !$articleMapper->keywordExists($keyword)) {
            $this->addMessage('unknownKeyword', 'danger');
            $this->redirect()
                ->to(['action' => 'index']);
        }

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'))
            ->add($this->getTranslator()->trans('menuKeywords'))
            ->add($keyword);
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuKeywords'), ['action' => 'index'])
            ->add($keyword, ['action' => 'show', 'keyword' => $keyword]);

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
            ->set('articles', $articleMapper->getArticlesByKeyword($keyword, $this->locale, $pagination))
            ->set('readAccess', $readAccess)
            ->set('pagination', $pagination);
    }
}
