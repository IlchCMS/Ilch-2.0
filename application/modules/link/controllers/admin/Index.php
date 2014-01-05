<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Controllers\Admin;

use Link\Controllers\Admin\Base as BaseController;
use Link\Mappers\Link as LinkMapper;
use Link\Models\Link as LinkModel;

defined('ACCESS') or die('no direct access');

class Index extends BaseController
{    
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewLink',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $linkMapper = new LinkMapper();
        $links = $linkMapper->getLinks();
        $this->getView()->set('links', $links);
    }

    public function deleteAction()
    {
        $linkMapper = new LinkMapper();
        $linkMapper->delete($this->getRequest()->getParam('id'));
        $this->addMessage('saveSuccess');
        $this->redirect(array('action' => 'index'));
    }

    public function treatAction()
    {
        $linkMapper = new LinkMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('link', $linkMapper->getLinkById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new LinkModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $model->setName($this->getRequest()->getPost('name'));
            $model->setBanner($this->getRequest()->getPost('banner'));
            $model->setLink($this->getRequest()->getPost('link'));

            $linkMapper->save($model);
            $this->addMessage('saveSuccess');
            $this->redirect(array('action' => 'index'));
        }
    }
}
