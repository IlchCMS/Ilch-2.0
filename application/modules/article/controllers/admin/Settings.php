<?php
/**
 * @copyright Ilch 2
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
                'name' => 'menuTemplates',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'templates', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
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
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'articlesPerPage' => 'numeric|integer|min:1',
                'articleRating' => 'numeric|integer|min:0|max:1',
                'disableComments' => 'numeric|integer|min:0|max:1',
                'boxArticleLimit' => 'numeric|integer|min:1',
                'boxArchiveLimit' => 'numeric|integer|min:1',
                'boxKeywordsH2' => 'required|numeric|integer|min:1',
                'boxKeywordsH3' => 'required|numeric|integer|min:1',
                'boxKeywordsH4' => 'required|numeric|integer|min:1',
                'boxKeywordsH5' => 'required|numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('article_articlesPerPage', $this->getRequest()->getPost('articlesPerPage'));
                $this->getConfig()->set('article_articleRating', $this->getRequest()->getPost('articleRating'));
                $this->getConfig()->set('article_disableComments', $this->getRequest()->getPost('disableComments'));
                $this->getConfig()->set('article_box_articleLimit', $this->getRequest()->getPost('boxArticleLimit'));
                $this->getConfig()->set('article_box_archiveLimit', $this->getRequest()->getPost('boxArchiveLimit'));
                
                $this->getConfig()->set('article_box_keywords', implode(',', [
                    $this->getRequest()->getPost('boxKeywordsH2'),
                    $this->getRequest()->getPost('boxKeywordsH3'),
                    $this->getRequest()->getPost('boxKeywordsH4'),
                    $this->getRequest()->getPost('boxKeywordsH5')]));

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

        $keywordsFontSizes = explode(',', $this->getConfig()->get('article_box_keywords'));
        $this->getView()->set('articlesPerPage', $this->getConfig()->get('article_articlesPerPage'))
            ->set('articleRating', $this->getConfig()->get('article_articleRating'))
            ->set('disableComments', $this->getConfig()->get('article_disableComments'))
            ->set('boxArticleLimit', $this->getConfig()->get('article_box_articleLimit'))
            ->set('boxArchiveLimit', $this->getConfig()->get('article_box_archiveLimit'))
            ->set('boxKeywordsH2', $keywordsFontSizes[0])
            ->set('boxKeywordsH3', $keywordsFontSizes[1])
            ->set('boxKeywordsH4', $keywordsFontSizes[2])
            ->set('boxKeywordsH5', $keywordsFontSizes[3]);
    }
}
