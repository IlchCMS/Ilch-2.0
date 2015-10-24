<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'guestbook',
            array
            (
                array
                (
                    'name' => 'Verwalten',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }
    
    public function indexAction() 
    {
        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('gbook_autosetfree', $this->getRequest()->getPost('entrySettings'));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('setfree', $this->getConfig()->get('gbook_autosetfree'));
    }
}
