<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Template as TemplateMapper;
use Ilch\Validation;

class Templates extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'menuTemplates',
                'active' => true,
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

        $this->getLayout()->addMenu(
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $templateMapper = new TemplateMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageTemplates'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_articles')) {
            foreach ($this->getRequest()->getPost('check_articles') as $articleId) {
                $templateMapper->delete($articleId);
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('templateMapper', $templateMapper);
        $this->getView()->set('articles', $templateMapper->getTemplates(null, $pagination));
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $templateMapper = new TemplateMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

        $this->getView()->set('article', $templateMapper->getTemplateById($this->getRequest()->getParam('id')));

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required',
                'content' => 'required',
            ]);

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

                $model->setAuthorId($this->getUser()->getId())
                    ->setDescription($this->getRequest()->getPost('description'))
                    ->setKeywords($this->getRequest()->getPost('keywords'))
                    ->setLocale($this->getRequest()->getPost('language'))
                    ->setTitle($this->getRequest()->getPost('title'))
                    ->setTeaser($this->getRequest()->getPost('teaser'))
                    ->setContent($this->getRequest()->getPost('content'))
                    ->setPerma($this->getRequest()->getPost('permaLink'))
                    ->setImage($this->getRequest()->getPost('image'))
                    ->setImageSource($this->getRequest()->getPost('imageSource'));
                $templateMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $templateMapper = new TemplateMapper();
            $templateMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
