<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Newsletter\Mappers\Newsletter as NewsletterMapper;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Mappers\User as UserMapper;

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
                    'name' => 'settings',
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

        
        
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_groups')) {
            foreach ($this->getRequest()->getPost('check_groups') as $groupId) {
                if ($groupId != 1) {
                    $groupMapper->delete($groupId);
                }
            }
        }
        
        $groupList = $groupMapper->getGroupList();
        $groupUsers = array();

        foreach ($groupList as $group) {
            $groupUsers[$group->getId()] = $groupMapper->getUsersForGroup($group->getId());
        }

        $this->getView()->set('groupUsersList', $groupUsers);
        $this->getView()->set('groupList', $groupList);
        $this->getView()->set('showDelGroupMsg', $this->getRequest()->getParam('showDelGroupMsg'));
        $this->getView()->set('errorMsg', $this->getRequest()->getParam('errorMsg'));
        
        
        
        
        
        $userMapper = new UserMapper();

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

        $userList = $userMapper->getUserList();
        $this->getView()->set('userList', $userList);
        $this->getView()->set('showDelUserMsg', $this->getRequest()->getParam('showDelUserMsg'));
        $this->getView()->set('errorMsg', $this->getRequest()->getParam('errorMsg'));
        
     
    }

    public function delAction()
    {
        $newsletterMapper = new NewsletterMapper();

        if ($this->getRequest()->isSecure()) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }

    public function showAction()
    {        
        $newsletterMapper = new NewsletterMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuNewsletter'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('show'), array('action' => 'show'));

        if ($this->getRequest()->isPost('delete')) {
            $newsletterMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(array('action' => 'index'));
        }

        $this->getView()->set('newsletter', $newsletterMapper->getNewsletterById($this->getRequest()->getParam('id')));
    }

    
}
