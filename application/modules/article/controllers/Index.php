<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Controllers;
use Article\Mappers\Article as ArticleMapper;
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
        $articleMapper = new ArticleMapper();
        $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'))
                ->add($article->getTitle(), array('action' => 'show', 'id' => $article->getId()));
        
        $this->getView()->set('article', $article, $this->_locale);
    }
}
