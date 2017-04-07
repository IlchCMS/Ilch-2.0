<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers\Admin;

use Ilch\Validation;

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
            $validation = Validation::create($this->getRequest()->getPost(), [
                'entrySettings' => 'required|numeric|integer|min:0|max:1',
                'entriesPerPage' => 'numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('gbook_autosetfree', $this->getRequest()->getPost('entrySettings'));
                $this->getConfig()->set('gbook_entriesPerPage', $this->getRequest()->getPost('entriesPerPage'));

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('setfree', $this->getConfig()->get('gbook_autosetfree'));
        $this->getView()->set('entriesPerPage', $this->getConfig()->get('gbook_entriesPerPage'));
    }
}
