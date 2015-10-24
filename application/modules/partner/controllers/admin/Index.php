<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Controllers\Admin;

use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Entry as PartnerModel;

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
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), array('action' => 'index'));

        $partnerMapper = new PartnerMapper();
        
        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_entries') as $partnerId) {
                    $partnerMapper->delete($partnerId);
                }
            }

            if ($this->getRequest()->getPost('action') == 'setfree') {
                foreach($this->getRequest()->getPost('check_entries') as $entryId) {
                    $model = new \Modules\Partner\Models\Entry();
                    $model->setId($entryId);
                    $model->setFree(1);
                    $partnerMapper->save($model);
                }
            }
        }

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
        if($this->getRequest()->isSecure()) {
            $partnerMapper = new PartnerMapper();
            $partnerMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function setfreeAction()
    {
        $partnerMapper = new PartnerMapper();
        $model = new \Modules\Partner\Models\Entry();
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
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('partner', $partnerMapper->getPartnerById($this->getRequest()->getParam('id')));
        } else {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));
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
                $model->setName($name);
                $model->setBanner($banner);
                $model->setLink($link);
                $partnerMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
