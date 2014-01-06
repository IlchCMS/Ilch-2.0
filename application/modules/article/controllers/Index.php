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
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticles'), array('action' => 'index'));

        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp')) {
            if ($this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
        }

        $articleMapper = new ArticleMapper();
        $this->getView()->set('articles', $articleMapper->getArticles($locale));
    }
}
