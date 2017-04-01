<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
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
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'articlesPerPage' => 'numeric|integer|min:1',
                'boxArticleLimit' => 'numeric|integer|min:1',
                'boxArchiveLimit' => 'numeric|integer|min:1',
                'boxKeywordsH2' => 'numeric|integer|min:1',
                'boxKeywordsH3' => 'numeric|integer|min:1',
                'boxKeywordsH4' => 'numeric|integer|min:1',
                'boxKeywordsH5' => 'numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('article_articlesPerPage', $this->getRequest()->getPost('articlesPerPage'));
                $this->getConfig()->set('article_box_articleLimit', $this->getRequest()->getPost('boxArticleLimit'));
                $this->getConfig()->set('article_box_archiveLimit', $this->getRequest()->getPost('boxArchiveLimit'));
                $this->getConfig()->set('article_box_keywordsH2', $this->getRequest()->getPost('boxKeywordsH2'));
                $this->getConfig()->set('article_box_keywordsH3', $this->getRequest()->getPost('boxKeywordsH3'));
                $this->getConfig()->set('article_box_keywordsH4', $this->getRequest()->getPost('boxKeywordsH4'));
                $this->getConfig()->set('article_box_keywordsH5', $this->getRequest()->getPost('boxKeywordsH5'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('articlesPerPage', $this->getConfig()->get('article_articlesPerPage'))
            ->set('boxArticleLimit', $this->getConfig()->get('article_box_articleLimit'))
            ->set('boxArchiveLimit', $this->getConfig()->get('article_box_archiveLimit'))
            ->set('boxKeywordsH2', $this->getConfig()->get('article_box_keywordsH2'))
            ->set('boxKeywordsH3', $this->getConfig()->get('article_box_keywordsH3'))
            ->set('boxKeywordsH4', $this->getConfig()->get('article_box_keywordsH4'))
            ->set('boxKeywordsH5', $this->getConfig()->get('article_box_keywordsH5'));
    }
}
