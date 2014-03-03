<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Controllers;
use Article\Mappers\Article as ArticleMapper;
use Comment\Mappers\Comment as CommentMapper;
use Comment\Models\Comment as CommentModel;

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
        
        $this->_locale = $locale;
    }

    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'));
        $articleMapper = new ArticleMapper();
        $this->getView()->set('articles', $articleMapper->getArticles($this->_locale));
    }
    
    public function showAction()
    {
        $commentMapper = new CommentMapper();

        if ($this->getRequest()->getPost('article_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('articles_'.$this->getRequest()->getParam('id'));
            $commentModel->setText($this->getRequest()->getPost('article_comment_text'));
            
            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $articleMapper = new ArticleMapper();

        $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));
        $comments = $commentMapper->getCommentsByKey('articles_'.$this->getRequest()->getParam('id'));

        $this->getView()->set('article', $article);
        $this->getView()->set('comments', $comments);
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'))
            ->add($article->getTitle(), array('action' => 'show', 'id' => $article->getId()));
    }
}
