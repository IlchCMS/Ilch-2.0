<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
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
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'));
        $articleMapper = new ArticleMapper();
        $this->getView()->set('articles', $articleMapper->getArticles($this->locale));
    }

    public function showAction()
    {
        $commentMapper = new CommentMapper();

        if ($this->getRequest()->getPost('article_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id'));
            $commentModel->setText($this->getRequest()->getPost('article_comment_text'));

            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        if($this->getRequest()->isPost() & $this->getRequest()->getParam('preview') == 'true') {
            $title = $this->getRequest()->getPost('articleTitle');
            $content = $this->getRequest()->getPost('articleContent');
            $image = $this->getRequest()->getPost('articleImage');

            $articleModel = new ArticleModel();
            $articleModel->setTitle($title);
            $articleModel->setContent($content);
            $articleModel->setArticleImage($image);

            $this->getView()->set('article', $articleModel);
        } else {
            $articleMapper = new ArticleMapper();
            $articleModel = new ArticleModel();

            $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));

            $this->getLayout()->set('metaTitle', $article->getTitle());
            $this->getLayout()->set('metaDescription', $article->getDescription());
            $this->getView()->set('article', $article);

            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'))
                ->add($article->getTitle(), array('action' => 'show', 'id' => $article->getId()));
        }

        $articleModel->setId($article->getId());
        $articleModel->setVisits($article->getVisits() + 1);
        $articleMapper->saveVisits($articleModel);

        $comments = $commentMapper->getCommentsByKey('article/index/show/id/'.$this->getRequest()->getParam('id'));
        $this->getView()->set('comments', $comments);
    }
}
