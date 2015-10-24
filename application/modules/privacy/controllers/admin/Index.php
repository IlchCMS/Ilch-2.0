<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Controllers\Admin;

use Modules\Privacy\Mappers\Privacy as PrivacyMapper;
use Modules\Privacy\Models\Privacy as PrivacyModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuPrivacy',
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

        $this->getLayout()->addMenuAction
                (
                array
                    (
                    'name' => 'add',
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPrivacy'), array('action' => 'index'));

        $privacyMapper = new PrivacyMapper();

        if ($this->getRequest()->getPost('check_privacys')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_privacys') as $privacyId) {
                    $privacyMapper->delete($privacyId);
                }
            }
        }

        $privacys = $privacyMapper->getPrivacy();

        $this->getView()->set('privacys', $privacys);
    }

    public function treatAction() 
    {
        $privacyMapper = new PrivacyMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('privacy', $privacyMapper->getPrivacyById($this->getRequest()->getParam('id')));
        }  else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuPrivacy'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));            
        }

        if ($this->getRequest()->isPost()) {
            $model = new PrivacyModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $text = trim($this->getRequest()->getPost('text'));

            if(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif(empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $model->setTitle($title);
                $model->setUrlTitle($this->getRequest()->getPost('urltitle'));
                $model->setUrl($this->getRequest()->getPost('url'));
                $model->setText($text);
                $model->setShow($this->getRequest()->getPost('show'));
                $privacyMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $privacyMapper = new PrivacyMapper();
            $privacyMapper->update($this->getRequest()->getParam('id'));

            $this->addMessage('saveSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $privacyMapper = new PrivacyMapper();
            $privacyMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
