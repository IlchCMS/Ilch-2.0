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
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'add',
                'active' => false,
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() == 'settings') {
            $items[2]['active'] = true;
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

    public function treatAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('event', $eventMapper->getEventById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $eventModel->setId($this->getRequest()->getParam('id'));
            }

            $title = trim($this->getRequest()->getPost('title'));
            $start = new \Ilch\Date(trim($this->getRequest()->getPost('start')));
            $place = trim($this->getRequest()->getPost('place'));
            $text = trim($this->getRequest()->getPost('text'));
            $show = trim($this->getRequest()->getPost('calendarShow'));

            if ($this->getRequest()->getPost('end') != '') {
                $end = new \Ilch\Date(trim($this->getRequest()->getPost('end')));
            }

            if (empty($start)) {
                $this->addMessage('missingDate', 'danger');
            } elseif (empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } elseif (empty($place)) {
                $this->addMessage('missingPlace', 'danger');
            } elseif (empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $eventModel->setUserId($this->getUser()->getId());
                $eventModel->setTitle($title);
                $eventModel->setStart($start);
                $eventModel->setEnd($end);
                $eventModel->setPlace($place);
                $eventModel->setText($text);
                $eventModel->setShow($show);
                $eventMapper->save($eventModel);

                $this->addMessage('saveSuccess');

                $this->redirect(['action' => 'index']);
            }
        }

        if ($eventMapper->existsTable('calendar') == true) {
            $this->getView()->set('calendarShow', 1);
        }
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
