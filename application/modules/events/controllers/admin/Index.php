<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Models\Events as EventModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
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
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $eventId) {
                    $eventMapper->delete($eventId);
                }
            }
        }

        $event = $eventMapper->getEntries();

        $this->getView()->set('event', $event);
    }

    public function showAction()
    {
        if ($this->getRequest()->isPost('delete')) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');

            $this->redirect(['action' => 'index']);
        }
        $eventMapper = new EventMapper();
        $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
