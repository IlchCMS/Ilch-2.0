<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Contact\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuReceivers',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuContact',
            $items
        );
    }
    
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuContact'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('contact_welcomeMessage', $this->getRequest()->getPost('welcomeMessage'));
            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('welcomeMessage', $this->getConfig()->get('contact_welcomeMessage'));
    }
}
