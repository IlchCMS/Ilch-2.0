<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuArticle',
            array
            (
                array
                (
                    'name' => 'menuArticles',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewSite',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('action' => 'index'));

        $articleMapper = new ArticleMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_articles')) {
            foreach($this->getRequest()->getPost('check_articles') as $articleId) {
                $articleMapper->delete($articleId);
            }
        }

        $articles = $articleMapper->getArticleList('');

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('articles', $articles);
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    public function deleteAction()
    {
        if($this->getRequest()->isSecure()) {
            $articleMapper = new ArticleMapper();
            $articleMapper->delete($this->getRequest()->getParam('id'));
        }

        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuActionNewSite'), array('action' => 'treat'));

        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $articleMapper = new ArticleMapper();

        if ($this->getRequest()->getParam('id')) {
            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('article', $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'), $locale));
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));

        if ($this->getRequest()->isPost()) {
            $model = new ArticleModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setDescription($this->getRequest()->getPost('description'));
            $model->setTitle($this->getRequest()->getPost('articleTitle'));
            $model->setContent($this->getRequest()->getPost('articleContent'));
            $model->setArticleImage($this->getRequest()->getPost('articleImage'));
            $model->setArticleImageSource($this->getRequest()->getPost('articleImageSource'));

            if ($this->getRequest()->getPost('articleLanguage') != '') {
                $model->setLocale($this->getRequest()->getPost('articleLanguage'));
            } else {
                $model->setLocale('');
            }

            $model->setPerma($this->getRequest()->getPost('articlePerma'));

            $articleMapper->save($model);

            $this->redirect(array('action' => 'index'));
        }
    }
}
