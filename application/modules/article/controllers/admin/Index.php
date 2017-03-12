<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_articles')) {
            foreach ($this->getRequest()->getPost('check_articles') as $articleId) {
                $articleMapper->delete($articleId);
                $commentMapper->deleteByKey('article/index/show/id/'.$articleId);
            }
        }

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('articles', $articleMapper->getArticleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    public function treatAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('article', $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'), $locale));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'cats' => '',
            'title' => '',
            'content' => '',
            'description' => '',
            'keywords' => '',
            'permaLink' => '',
            'image' => '',
            'imageSource' => '',
        ];

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL if image starts with application to get a complete URL for validation
            $image = $this->getRequest()->getPost('image');
            if (!empty($image)) {
                if (substr($image, 0, 11) == 'application') {
                    $image = BASE_URL.'/'.$image;
                }
            }

            // Create full-url of permaLink.
            $permaLink = BASE_URL.'/index.php/'.$this->getRequest()->getPost('permaLink');

            $post = [
                'cats' => $this->getRequest()->getPost('cats'),
                'title' => $this->getRequest()->getPost('title'),
                'content' => $this->getRequest()->getPost('content'),
                'description' => $this->getRequest()->getPost('description'),
                'keywords' => $this->getRequest()->getPost('keywords'),
                'permaLink' => $permaLink,
                'image' => $image,
                'imageSource' => $this->getRequest()->getPost('imageSource'),
            ];

            $validation = Validation::create($post, [
                'cats' => 'required|numeric|integer|min:1',
                'title' => 'required',
                'content' => 'required',
                'permaLink' => 'url',
                'image' => 'url',
            ]);

            // Restore original values
            $post['image'] = $this->getRequest()->getPost('image');
            $post['permaLink'] = $this->getRequest()->getPost('permaLink');

            if ($validation->isValid()) {
                $model = new ArticleModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                if ($this->getRequest()->getPost('language') != '') {
                    $model->setLocale($this->getRequest()->getPost('language'));
                } else {
                    $model->setLocale('');
                }
                $model->setCatId($post['cats']);
                $model->setAuthorId($this->getUser()->getId());
                $model->setDescription($post['description']);
                $model->setKeywords($post['keywords']);
                $model->setTitle($post['title']);
                $model->setContent($post['content']);
                $model->setPerma($post['permaLink']);
                $model->setArticleImage($post['image']);
                $model->setArticleImageSource($post['imageSource']);
                $articleMapper->save($model);

                $this->redirect(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('cats', $categoryMapper->getCategories());
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $articleMapper = new ArticleMapper();
            $commentMapper = new CommentMapper();
            $articleMapper->delete($this->getRequest()->getParam('id'));
            $commentMapper->deleteByKey('article/index/show/id/'.$this->getRequest()->getParam('id'));
        }

        $this->redirect(['action' => 'index']);
    }
}
