<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Page as PageModel;

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

        if ($this->getRequest()->isPost()) {
            $model = new PageModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setDescription($this->getRequest()->getPost('description'));
            $model->setKeywords($this->getRequest()->getPost('keywords'));
            $model->setTitle($this->getRequest()->getPost('pageTitle'));
            $model->setContent($this->getRequest()->getPost('pageContent'));
            
            if ($this->getRequest()->getPost('pageLanguage') != '') {
                $model->setLocale($this->getRequest()->getPost('pageLanguage'));
            } else {
                $model->setLocale('');
            }

            $model->setPerma($this->getRequest()->getPost('pagePerma'));

            $pageMapper->save($model);

            $this->redirect(['action' => 'index']);
        }

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
