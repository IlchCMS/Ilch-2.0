<?php
/**
 * @copyright Ilch 2.0
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
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

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

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') === 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $eventId) {
                    $eventMapper->delete($eventId);
                }
            }
        }

        $this->getView()->set('event', $eventMapper->getEntries());
    }

    public function showAction()
    {
        $eventMapper = new EventMapper();

        if ($this->getRequest()->isPost('delete')) {
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
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
