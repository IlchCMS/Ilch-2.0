<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Ilch\Controller\Admin;

class Settings extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuDownloads',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('downloads'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('downloads_downloadsPerPage', $this->getRequest()->getPost('downloadsPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('downloadsPerPage', $this->getConfig()->get('downloads_downloadsPerPage'));
    }
}
