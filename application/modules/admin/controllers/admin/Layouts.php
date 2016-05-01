<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

class Layouts extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = array
        (
            array
                (
                    'name' => 'layouts',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'layouts', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'search',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'layouts', 'action' => 'search'))
                ),
        );

        if ($this->getRequest()->getActionName() == 'index') {
            $items[0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'search') {
            $items[1]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('layouts'), array('action' => 'index'));

        $layouts = array();

        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            $model = new \Modules\Admin\Models\Layout();
            $model->setKey(basename($layoutPath));
            include_once $layoutPath.'/config/config.php';
            $model->setName($config['name']);
            $model->setAuthor($config['author']);
            if(!empty($config['link'])) {
                $model->setLink($config['link']);
            }
            $model->setDesc($config['desc']);
            if(!empty($config['modulekey'])) {
                $model->setModulekey($config['modulekey']);
            }
            $layouts[] = $model;
        }

        $this->getView()->set('defaultLayout', $this->getConfig()->get('default_layout'));
        $this->getView()->set('layouts', $layouts);
    }

    public function defaultAction()
    {
        $this->getConfig()->set('default_layout', $this->getRequest()->getParam('key'));
        $this->redirect(array('action' => 'index'));
    }

    public function deleteAction()
    {
        if ($this->getConfig()->get('default_layout') == $this->getRequest()->getParam('key')) {
            $this->addMessage('cantDeleteDefaultLayout');
        } else {
            removeDir(APPLICATION_PATH.'/layouts/'.$this->getRequest()->getParam('key'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function searchAction()
    {
        if ($this->getRequest()->isPost('layout')) {
            $transfer = new \Ilch\Transfer();
            $transfer->setZipSavePath(ROOT_PATH.'/updates/');
            $transfer->setDownloadUrl($this->getRequest()->getPost('url'));
            $transfer->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
            $transfer->setCurlOpt(CURLOPT_FAILONERROR, true);
            
            $transfer->save();
            $transfer->install();
            $this->addMessage('Success');
        }
    }
}
