<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Controllers\Admin;

use Partner\Mappers\Partner as PartnerMapper;
use Partner\Models\Entry as PartnerModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuPartner',
            array
            (
                array
                (
                    'name' => 'manage',
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
                'name' => 'menuActionNewPartner',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $partnerMapper->getEntries(array('setfree' => 0));
        } else {
            $entries = $partnerMapper->getEntries(array('setfree' => 1));
        }

        $this->getView()->set('entries', $entries);
        $this->getView()->set('badge', count($partnerMapper->getEntries(array('setfree' => 0))));
    }
    
    public function delAction()
    {
        $partnerMapper = new PartnerMapper();
        $partnerMapper->delete($this->getRequest()->getParam('id'));
        
        $this->addMessage('deleteSuccess');
        
        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(array('action' => 'index', 'showsetfree' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }
    
    public function setfreeAction()
    {
        $partnerMapper = new PartnerMapper();
        $model = new \Partner\Models\Entry();
        $model->setId($this->getRequest()->getParam('id'));
        $model->setFree(1);
        $partnerMapper->save($model);
            
        $this->addMessage('freeSuccess');

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(array('action' => 'index', 'showsetfree' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function treatAction() 
    {
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('partner', $partnerMapper->getPartnerById($this->getRequest()->getParam('id')));
        }

        if ($this->getRequest()->isPost()) {
            $model = new PartnerModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $name = $this->getRequest()->getPost('name');
            $banner = trim($this->getRequest()->getPost('banner'));
            $link = trim($this->getRequest()->getPost('link'));
            
            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($link)) {
                $this->addMessage('missingLink', 'danger');
            } elseif(empty($banner)) {
                $this->addMessage('missingBanner', 'danger');
            } else {
                $model->setFree(1);
                $model->setName($this->getRequest()->getPost('name'));
                $model->setBanner($this->getRequest()->getPost('banner'));
                $model->setLink($this->getRequest()->getPost('link'));
                $partnerMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
