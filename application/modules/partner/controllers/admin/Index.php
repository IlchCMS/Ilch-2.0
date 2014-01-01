<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Controllers\Admin;
use Partner\Mappers\Partner as PartnerMapper;
use Partner\Models\Partner as PartnerModel;

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
                    'name' => 'menuPartners',
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
        $partners = $partnerMapper->getPartners();
        $this->getView()->set('partners', $partners);
    }

    public function deleteAction()
    {
        $partnerMapper = new PartnerMapper();
        $partnerMapper->delete($this->getRequest()->getParam('id'));
        $this->redirect(array('action' => 'index'));
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

            $model->setName($this->getRequest()->getPost('name'));
            $model->setBanner($this->getRequest()->getPost('banner'));
            $model->setLink($this->getRequest()->getPost('link'));

            $partnerMapper->save($model);
            $this->addMessage('saveSuccess');
            $this->redirect(array('action' => 'index'));
        }
    }
}
