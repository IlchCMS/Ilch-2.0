<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Article\Mappers\Template as TemplateMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\User\Mappers\Group as GroupMapper;
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
                'name' => 'menuTemplates',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'templates', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() === 'index' && $this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $articleMapper = new ArticleMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_articles')) {
            $commentMapper = new CommentMapper();
            foreach ($this->getRequest()->getPost('check_articles') as $articleId) {
                $articleMapper->deleteWithComments($articleId, $commentMapper);
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('articles', $articleMapper->getArticles('', $pagination));
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $templateMapper = new TemplateMapper();
        $groupMapper = new GroupMapper();

        $groupsList = $groupMapper->getGroupList();
        $locale = $this->getRequest()->getParam('locale');

        if ($this->getRequest()->getParam('template')) {
            $this->getView()->set('template', $this->getRequest()->getParam('template'));
            $this->getView()->set('article', $templateMapper->getTemplateById($this->getRequest()->getParam('template')));
        } elseif ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
            $this->getView()->set('article', $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'), $locale));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'groups' => 'visibleFor',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'cats' => 'required',
                'title' => 'required',
                'content' => 'required',
                'groups' => 'required',
            ]);

            if ($validation->isValid()) {
                $catIds = implode(',', $this->getRequest()->getPost('cats'));
                $model = new ArticleModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                if ($this->getRequest()->getPost('language') != '') {
                    $model->setLocale($this->getRequest()->getPost('language'));
                } else {
                    $model->setLocale('');
                }

                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    $groups = implode(',', $this->getRequest()->getPost('groups'));
                }

                $model->setCatId($catIds)
                    ->setAuthorId($this->getUser()->getId())
                    ->setDescription($this->getRequest()->getPost('description'))
                    ->setKeywords($this->getRequest()->getPost('keywords'))
                    ->setTitle($this->getRequest()->getPost('title'))
                    ->setDateCreated(new \Ilch\Date($this->getRequest()->getPost('date_created')))
                    ->setTeaser($this->getRequest()->getPost('teaser'))
                    ->setContent($this->getRequest()->getPost('content'))
                    ->setPerma($this->getRequest()->getPost('permaLink'))
                    ->setTopArticle($this->getRequest()->getPost('topArticle'))
                    ->setCommentsDisabled($this->getRequest()->getPost('commentsDisabled'))
                    ->setReadAccess($groups)
                    ->setImage($this->getRequest()->getPost('image'))
                    ->setImageSource($this->getRequest()->getPost('imageSource'));
                $articleId = $articleMapper->save($model);
                if ($this->getRequest()->getPost('saveAsTemplate')) {
                    $templateMapper->save($model);
                }

                if (!$model->getId()) {
                    foreach ($groupsList as $key => $group) {
                        if ($group->getId() !== 1) {
                            $groupMapper->saveAccessData($group->getId(), $articleId, 1, 'article');
                        }
                    }
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            if ($this->getRequest()->getParam('id')) {
                $redirect = ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')];
            } else {
                $redirect = ['action' => 'treat'];
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to($redirect);
        }

        if ($this->getRequest()->getParam('id')) {
            $groups = explode(',', $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'))->getReadAccess());
        } else {
            $groups = [1,2,3];
        }

        $this->getView()->set('templates', $templateMapper->getTemplates($locale));
        $this->getView()->set('cats', $categoryMapper->getCategories());
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('userGroupList', $groupsList);
        $this->getView()->set('groups', $groups);
        $this->getView()->set('disableComments', (bool)$this->getConfig()->get('article_disableComments'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $articleMapper = new ArticleMapper();
            $articleMapper->deleteWithComments($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
