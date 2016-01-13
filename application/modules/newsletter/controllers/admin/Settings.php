<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuNewsletter', 
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'receiver',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
            )
        );
    }

    public function indexAction()
    {
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));
        
        if ($this->getRequest()->isPost()) {
            /*
            if ($this->getRequest()->getPost('all') == 'Alle') {
                $this->getConfig()->set('newsletter_config','all');
            }
            if ($this->getRequest()->getPost('group') == 'Gruppe') {
                $this->getConfig()->set('newsletter_config','group');
            }
            if ($this->getRequest()->getPost('user') == 'Einzelne User') {
                $this->getConfig()->set('newsletter_config','user');
            }
             */      
            $newsletterModel = new NewsletterModel();
            
            foreach ($this->getRequest()->getPost('check_users') as $userEmail) {           
                if ($userEmail != '') {
                    $newsletterModel->setEmail($userEmail);
                    $newsletterMapper->saveEmail($newsletterModel);
                    //$newsletterMapper->activeEmail($newsletterModel);
                }      
            }
            $this->addMessage('saveSuccess');
        }
        
        $emails = $newsletterMapper->getMail();
        $this->getView()->set('emails', $emails);
        
        $this->getView()->set('newsletter_config', $this->getConfig()->get('newsletter_config')); 
        /*
        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach($this->getRequest()->getPost('check_users') as $userId) {
                $deleteUser = $userMapper->getUserById($userId);

                if ($deleteUser->getId() != Registry::get('user')->getId()) {
                    if($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() == 1) {} else {
                        $userMapper->delete($deleteUser->getId());
                    }
                }
            }
        }
        */
        $userList = $newsletterMapper->getSendMailUser();
        $this->getView()->set('userList', $userList);

    }   
}
