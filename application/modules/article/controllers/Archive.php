<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Modules\Article\Mappers\Article as ArticleMapper;

class Archive extends \Ilch\Controller\Frontend
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

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuArchives'), array('action' => 'index'));

        $this->getView()->set('articles', $articleMapper->getArticles($this->locale));
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();

        $date = new \Ilch\Date(''.$this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01');
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuArchives'), array('action' => 'index'))
                ->add($date->format('F Y', true), array('action' => 'show', 'year' => $this->getRequest()->getParam('year'), 'month' => $this->getRequest()->getParam('month')));

        $date = $this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month').'-01';
        $this->getView()->set('articles', $articleMapper->getArticlesByDate($date));
    }
}
