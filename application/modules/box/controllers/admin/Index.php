<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Box\Controllers\Admin;
use Box\Mappers\Box as BoxMapper;
use Box\Models\Box as BoxModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuBox',
            array
            (
                array
                (
                    'name' => 'menuBoxes',
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
                'name' => 'menuActionNewBox',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $boxMapper = new BoxMapper();
        $boxes = $boxMapper->getBoxList($this->getTranslator()->getLocale());
        $this->getView()->set('boxes', $boxes);
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        $boxMapper = new BoxMapper();
        $boxMapper->delete($this->getRequest()->getParam('id'));
        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $boxMapper = new BoxMapper();

        if ($this->getRequest()->getParam('id')) {
            if ($this->getRequest()->getParam('locale') == '') {
                $locale = $this->getTranslator()->getLocale();
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('box', $boxMapper->getBoxByIdLocale($this->getRequest()->getParam('id'), $locale));
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));

        if ($this->getRequest()->isPost()) {
            $model = new BoxModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setTitle($this->getRequest()->getPost('boxTitle'));
            $model->setContent($this->getRequest()->getPost('boxContent'));
            
            if ($this->getRequest()->getPost('boxLanguage') != '') {
                $model->setLocale($this->getRequest()->getPost('boxLanguage'));
            } else {
                $model->setLocale($this->getTranslator()->getLocale());
            }

            $boxMapper->save($model);

            $this->redirect(array('action' => 'index'));
        }
    }
}
