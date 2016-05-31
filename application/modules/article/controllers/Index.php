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

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index']);

        $this->getView()->set('articles', $articleMapper->getArticles($this->locale));
    }

    public function showAction()
    {
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
        }

        if ($this->getRequest()->isPost() & $this->getRequest()->getParam('preview') == 'true') {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('preview'), ['action' => 'index']);

            $title = $this->getRequest()->getPost('title');
            $catId = $this->getRequest()->getPost('cats');
            $content = $this->getRequest()->getPost('content');
            $image = $this->getRequest()->getPost('image');

            $categoryMapper = new CategoryMapper();
            $articleModel = new ArticleModel();

            $articleModel->setTitle($title);
            $articleModel->setCatId($catId);
            $articleModel->setContent($content);
            $articleModel->setArticleImage($image);
            $articleModel->setVisits(0);

            $this->getView()->set('article', $articleModel);
            $this->getView()->set('categoryMapper', $categoryMapper);
            $this->getView()->set('commentMapper', $commentMapper);
        } else {
            $articleMapper = new ArticleMapper();
            $articleModel = new ArticleModel();
            $categoryMapper = new CategoryMapper();

            $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));
            $articlesCats = $categoryMapper->getCategoryById($article->getCatId());

            $this->getLayout()->set('metaTitle', $article->getTitle());
            $this->getLayout()->set('metaDescription', $article->getDescription());
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCats'), ['controller' => 'cats', 'action' => 'index'])
                    ->add($articlesCats->getName(), ['controller' => 'cats', 'action' => 'show', 'id' => $articlesCats->getId()])
                    ->add($article->getTitle(), ['action' => 'show', 'id' => $article->getId()]);

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
