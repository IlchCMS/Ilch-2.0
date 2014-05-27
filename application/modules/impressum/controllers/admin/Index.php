<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Impressum\Controllers\Admin;

use Modules\Impressum\Mappers\Impressum as ImpressumMapper;
use Modules\Impressum\Models\Impressum as ImpressumModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuImpressum',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );
    }



    public function indexAction()
    {
        $impressumMapper = new ImpressumMapper();

        $this->getView()->set('impressum', $impressumMapper->getImpressumById(1));
              
        if ($this->getRequest()->isPost()) {
            $model = new ImpressumModel();

            $model->setId(1);
            
            $model->setParagraph($this->getRequest()->getPost('paragraph'));
            $model->setCompany($this->getRequest()->getPost('company'));
            $model->setName($this->getRequest()->getPost('name'));
            $model->setAddress($this->getRequest()->getPost('address'));
            $model->setCity($this->getRequest()->getPost('city'));
            $model->setPhone($this->getRequest()->getPost('phone'));
            $model->setDisclaimer($this->getRequest()->getPost('disclaimer'));
            $impressumMapper->save($model);

            $this->addMessage('saveSuccess');

            $this->redirect(array('action' => 'index'));
        }
    }
}
