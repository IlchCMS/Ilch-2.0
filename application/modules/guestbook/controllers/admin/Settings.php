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
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'guestbook',
            $items
        );
    }
    
    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('gbook_autosetfree', $this->getRequest()->getPost('entrySettings'));
            $this->getConfig()->set('gbook_entriesPerPage', $this->getRequest()->getPost('entriesPerPage'));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('setfree', $this->getConfig()->get('gbook_autosetfree'));
        $this->getView()->set('entriesPerPage', $this->getConfig()->get('gbook_entriesPerPage'));
    }
}
