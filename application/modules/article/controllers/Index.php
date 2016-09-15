<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

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
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('article_articlesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('article_articlesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('articles', $articleMapper->getArticles($this->locale, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {                
                $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id').'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id'));                
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
            $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
        }
        if ($this->getRequest()->getParam('commentId') AND ($this->getRequest()->getParam('key') == 'up' OR $this->getRequest()->getParam('key') == 'down')) {
            $id = $this->getRequest()->getParam('id');
            $commentId = $this->getRequest()->getParam('commentId');
            $key = $this->getRequest()->getParam('key');

            $commentMapper->updateLike($commentId, $key);

            $this->redirect(['action' => 'show', 'id' => $id.'#comment_'.$commentId]);
        }

        if ($this->getRequest()->isPost() & $this->getRequest()->getParam('preview') == 'true') {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('preview'), ['action' => 'index']);

            $title = $this->getRequest()->getPost('title');
            $catId = $this->getRequest()->getPost('cats');
            $content = $this->getRequest()->getPost('content');
            $image = $this->getRequest()->getPost('image');

            $articleModel = new ArticleModel();
            $articleModel->setTitle($title);
            $articleModel->setCatId($catId);
            $articleModel->setContent($content);
            $articleModel->setArticleImage($image);
            $articleModel->setVisits(0);

            $this->getView()->set('categoryMapper', $categoryMapper);
            $this->getView()->set('commentMapper', $commentMapper);
            $this->getView()->set('article', $articleModel);
        } else {
            $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));
            $articlesCats = $categoryMapper->getCategoryById($article->getCatId());

            $this->getLayout()->getTitle()
                    ->add($article->getTitle());
            $this->getLayout()->set('metaDescription', $article->getDescription());
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['controller' => 'cats', 'action' => 'index'])
                    ->add($articlesCats->getName(), ['controller' => 'cats', 'action' => 'show', 'id' => $articlesCats->getId()])
                    ->add($article->getTitle(), ['action' => 'show', 'id' => $article->getId()]);

            $articleModel = new ArticleModel();
            $articleModel->setId($article->getId());
            $articleModel->setVisits($article->getVisits() + 1);
            $articleMapper->saveVisits($articleModel);

            $this->getView()->set('categoryMapper', $categoryMapper);
            $this->getView()->set('commentMapper', $commentMapper);
            $this->getView()->set('userMapper', $userMapper);
            $this->getView()->set('config', $config);
            $this->getView()->set('article', $article);
            $this->getView()->set('comments', $commentMapper->getCommentsByKey('article/index/show/id/'.$this->getRequest()->getParam('id')));
        }
    }
}
