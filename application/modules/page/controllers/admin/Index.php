<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page\Controllers\Admin;
use Page\Mappers\Page as PageMapper;
use Page\Models\Page as PageModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuSite',
            array
            (
                array
                (
                    'name' => 'menuSites',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewSite',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $pageMapper = new PageMapper();
        $pages = $pageMapper->getPageList($this->getTranslator()->getLocale());
        $this->getView()->set('pages', $pages);
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        $pageMapper = new PageMapper();
        $pageMapper->delete($this->getRequest()->getParam('id'));
        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $pageMapper = new PageMapper();

        if ($this->getRequest()->getParam('id')) {
            if ($this->getRequest()->getParam('locale') == '') {
                $locale = $this->getTranslator()->getLocale();
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('page', $pageMapper->getPageByIdLocale($this->getRequest()->getParam('id'), $locale));
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));

        if ($this->getRequest()->isPost()) {
            $model = new PageModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setTitle($this->getRequest()->getPost('pageTitle'));
            $model->setContent($this->getRequest()->getPost('pageContent'));
            
            if ($this->getRequest()->getPost('pageLanguage') != '') {
                $model->setLocale($this->getRequest()->getPost('pageLanguage'));
            } else {
                $model->setLocale($this->getTranslator()->getLocale());
            }

            $model->setPerma($this->getRequest()->getPost('pagePerma'));
            $pageMapper->save($model);

            $this->redirect(array('action' => 'index'));
        }
    }
}
