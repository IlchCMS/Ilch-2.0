<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Ilch\Comments;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Article\Config\Config as ArticleConfig;
use Ilch\Layout\Helper\MetaTag\Model as MetaTagModel;

class Index extends \Ilch\Controller\Frontend
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
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index']);

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
            ->set('articles', $articleMapper->getArticles($this->locale, $pagination))
            ->set('pagination', $pagination)
            ->set('readAccess', $readAccess);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');
        $hasReadAccess = true;
        $article = null;

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index']);

        // Preview from creating/editing an article
        if ($this->getRequest()->isPost() & $this->getRequest()->getParam('preview') === 'true') {
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('preview'));
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('preview'), ['action' => 'index']);

            $catIds = '';
            if ($this->getRequest()->getPost('cats')) {
                $catIds = implode(',', $this->getRequest()->getPost('cats'));
            }

            $article = new ArticleModel();
            $article->setTitle($this->getRequest()->getPost('title'))
                ->setTeaser($this->getRequest()->getPost('teaser'))
                ->setCatId($catIds)
                ->setKeywords($this->getRequest()->getPost('keywords'))
                ->setContent($this->getRequest()->getPost('content'))
                ->setImage($this->getRequest()->getPost('image'))
                ->setImageSource($this->getRequest()->getPost('imageSource'))
                ->setVisits(0);
        } else {
            $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'), $this->locale);
        }

        if ($article === null) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Article', 'errorText' => 'notFound']);
            return;
        }

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

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        $hasReadAccess = (is_in_array($readAccess, explode(',', $article->getReadAccess())) || $adminAccess === true);

        if ($hasReadAccess && !$article->getCommentsDisabled()) {
            if ($this->getRequest()->getPost('saveComment')) {
                $comments = new Comments();
                $key = sprintf(ArticleConfig::COMMENT_KEY_TPL, $this->getRequest()->getParam('id'));

                if ($this->getRequest()->getPost('fkId')) {
                    $key .= '/id_c/'.$this->getRequest()->getPost('fkId');
                }

                $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
            }

            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'show', 'id' => $id.'#comment_'.$commentId]);
            }
        }

        $this->getLayout()->header()
            ->css('static/css/article.css')
            ->css('../comment/static/css/comment.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuCats'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuCats'), ['controller' => 'cats', 'action' => 'index']);

        $catIds = [];
        if ($article->getCatId()) {
            $catIds = explode(',', $article->getCatId());
        }

        foreach ($catIds as $catId) {
            $articlesCats = $categoryMapper->getCategoryById($catId);
            $this->getLayout()->getTitle()->add($articlesCats->getName());
            $this->getLayout()->getHmenu()->add($articlesCats->getName(), ['controller' => 'cats', 'action' => 'show', 'id' => $catId]);
        }

        if ($hasReadAccess) {
            $this->getLayout()->getTitle()->add($article->getTitle());
            $this->getLayout()->getHmenu()->add($article->getTitle(), ['action' => 'show', 'id' => $article->getId()]);

            $this->getLayout()->set('metaDescription', $article->getDescription());
            $this->getLayout()->set('metaKeywords', $article->getKeywords());

            $metaTagModel = new MetaTagModel();
            $metaTagModel->setName('og:title')
                ->setContent($article->getTitle());
            $this->getLayout()->add('metaTags', 'og:title', $metaTagModel);

            if (!empty($article->getDescription())) {
                $metaTagModel = new MetaTagModel();
                $metaTagModel->setName('og:description')
                    ->setContent($article->getDescription());
                $this->getLayout()->add('metaTags', 'og:description', $metaTagModel);
            }

            $metaTagModel = new MetaTagModel();
            $metaTagModel->setName('og:type')
                ->setContent('article');
            $this->getLayout()->add('metaTags', 'og:type', $metaTagModel);

            if (!empty($article->getImage())) {
                $metaTagModel = new MetaTagModel();
                $metaTagModel->setName('og:image')
                    ->setContent(BASE_URL.'/'.$article->getImage());
                $this->getLayout()->add('metaTags', 'og:image', $metaTagModel);
            }

            if (!empty($article->getLocale())) {
                $metaTagModel = new MetaTagModel();
                $metaTagModel->setName('og:locale')
                    ->setContent($article->getLocale());
                $this->getLayout()->add('metaTags', 'og:locale', $metaTagModel);
            }
        }

        $articleModel = new ArticleModel();
        $articleModel->setId($article->getId())
            ->setVisits($article->getVisits() + 1);
        $articleMapper->saveVisits($articleModel);

        $this->getView()->set('userMapper', $userMapper)
            ->set('commentsCount', $commentMapper->getCountComments(sprintf(ArticleConfig::COMMENT_KEY_TPL, $this->getRequest()->getParam('id'))))
            ->set('categoryMapper', $categoryMapper)
            ->set('config', $config)
            ->set('article', $article)
            ->set('hasReadAccess', $hasReadAccess);
    }

    public function voteAction()
    {
        $id = $this->getRequest()->getParam('id');

        if ($this->getConfig()->get('article_articleRating')) {
            $articleMapper = new ArticleMapper();
            $userMapper = New UserMapper();

            $article = $articleMapper->getArticleByIdLocale($id, $this->locale);

            // Check readaccess
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

            $adminAccess = null;
            if ($this->getUser()) {
                $adminAccess = $this->getUser()->isAdmin();
            }

            if ($article !== null) {
                $hasReadAccess = (is_in_array($readAccess, explode(',', $article->getReadAccess())) || $adminAccess === true);

                if ($hasReadAccess) {
                    $articleMapper->saveVotes($id, $this->getUser()->getId());
                }
            }
        }

        $this->redirect(['action' => $this->getRequest()->getParam('from'), 'id' => $id]);
    }

    public function rssAction()
    {
        $articleMapper = new ArticleMapper();
        $userMapper = new UserMapper();

        $user = null;
        // FIXME: Only create RSS-Feed with guest rights to avoid leaking articles not supposed to be for everyone.
        // This (unfinished) feature needs a complete rewrite to be ready.
        // http://redmine.ilch2.de/issues/591
        // if ($this->getUser()) {
            // $user = $userMapper->getUserById($this->getUser()->getId());
        // }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('userMapper', $userMapper)
            ->set('siteTitle', $this->getConfig()->get('page_title'))
            ->set('articles', $articleMapper->getArticles($this->locale))
            ->set('readAccess', $readAccess);
    }
}
