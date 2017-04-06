<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Page as PageModel;
use Ilch\Validation;

class Page extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'page', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'page', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuSites',
            $items
        );
    }

    public function indexAction()
    {
        $pageMapper = new PageMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_pages')) {
            foreach ($this->getRequest()->getPost('check_pages') as $pageId) {
                $pageMapper->delete($pageId);
            }
        }

        $this->getView()->set('pageMapper', $pageMapper);
        $this->getView()->set('pages', $pageMapper->getPageList(''));
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    public function treatAction()
    {
        $pageMapper = new PageMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('page', $pageMapper->getPageByIdLocale($this->getRequest()->getParam('id'), $locale));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSites'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'pageTitle' => '',
            'pageContent' => '',
            'pageLanguage' => '',
            'keywords' => '',
            'description' => '',
            'permaLink' => ''
        ];

        if ($this->getRequest()->isPost()) {
            // Create full-url of permaLink.
            $entityMap = [
                "&" => "",
                "<" => "",
                ">" => "",
                '"' => "",
                "'" => "",
                "/" => "",
                "(" => "",
                ")" => "",
                ";" => ""
            ];

            $permaLink = strtr($this->getRequest()->getPost('permaLink'), $entityMap);

            if ($permaLink != '') {
                $permaLinkUrl = BASE_URL.'/index.php/'.$permaLink;
            } else {
                $permaLinkUrl = '';
            }

            $post = [
                'pageTitle' => $this->getRequest()->getPost('pageTitle'),
                'pageContent' => trim($this->getRequest()->getPost('pageContent')),
                'pageLanguage' => $this->getRequest()->getPost('pageLanguage'),
                'keywords' => $this->getRequest()->getPost('keywords'),
                'description' => trim($this->getRequest()->getPost('description')),
                'permaLink' => $permaLinkUrl,
            ];

            $validation = Validation::create($post, [
                'pageTitle' => 'required',
                'pageContent' => 'required',
                'permaLink' => 'url|required'
            ]);

            // Restore original values
            $post['permaLink'] = $permaLink;

            if ($validation->isValid()) {
                $model = new PageModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setTitle($post['pageTitle']);
                $model->setContent($post['pageContent']);
                $model->setKeywords($post['keywords']);
                $model->setDescription($post['description']);
                if ($this->getRequest()->getPost('pageLanguage') != '') {
                    $model->setLocale($post['pageLanguage']);
                } else {
                    $model->setLocale('');
                }
                $model->setPerma($permaLink);
                $pageMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $pageMapper = new PageMapper();
            $pageMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
