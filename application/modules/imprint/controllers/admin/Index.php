<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Controllers\Admin;

use Modules\Imprint\Mappers\Imprint as ImprintMapper;
use Modules\Imprint\Models\Imprint as ImprintModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuImprint',
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
                ->add($this->getTranslator()->trans('menuImprint'), array('action' => 'index'));

        $imprintMapper = new ImprintMapper();

        $this->getView()->set('imprint', $imprintMapper->getImprintById(1));
        $this->getView()->set('imprintStyle', $this->getConfig()->get('imprint_style'));
              
        if ($this->getRequest()->isPost()) {
            $model = new ImprintModel();
            $model->setId(1);            
            $model->setParagraph($this->getRequest()->getPost('paragraph'));
            $model->setCompany($this->getRequest()->getPost('company'));
            $model->setName($this->getRequest()->getPost('name'));
            $model->setAddress($this->getRequest()->getPost('address'));
            $model->setAddressAdd($this->getRequest()->getPost('addressadd'));
            $model->setCity($this->getRequest()->getPost('city'));
            $model->setPhone($this->getRequest()->getPost('phone'));
            $model->setFax($this->getRequest()->getPost('fax'));
            $model->setEmail($this->getRequest()->getPost('email'));
            $model->setRegistration($this->getRequest()->getPost('registration'));
            $model->setCommercialRegister($this->getRequest()->getPost('commercialregister'));
            $model->setVatId($this->getRequest()->getPost('vatid'));
            $model->setOther($this->getRequest()->getPost('other'));
            $model->setDisclaimer($this->getRequest()->getPost('disclaimer'));
            $imprintMapper->save($model);

            $this->addMessage('saveSuccess');

            $this->redirect(array('action' => 'index'));
        }
    }
}
