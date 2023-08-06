<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\Events\Mappers\Events as EventMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'], '')
                ]
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() === 'index' && $this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuEvents',
            $items
        );
    }

    public function indexAction()
    {
        $eventMapper = new EventMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_entries') as $eventId) {
                $eventMapper->delete($eventId);
            }
        }

        $this->getView()->set('event', $eventMapper->getEntries());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->redirect()
            ->to(['action' => 'index']);
    }
}
