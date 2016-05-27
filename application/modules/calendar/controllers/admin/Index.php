<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Controllers\Admin;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\Calendar\Models\Calendar as CalendarModel;

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
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[1]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuCalendar',
            $items
        );
    }

    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $calendarId) {
                    $calendarMapper->delete($calendarId);
                }
            }
        }

        $this->getView()->set('calendar', $calendarMapper->getEntries());
    }

    public function treatAction()
    {
        $calendarMapper = new CalendarMapper();
        $calendarModel = new CalendarModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('calendar', $calendarMapper->getCalendarById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $calendarModel->setId($this->getRequest()->getParam('id'));
            }
            
            $title = trim($this->getRequest()->getPost('title'));
            $place = trim($this->getRequest()->getPost('place'));
            $start = new \Ilch\Date(trim($this->getRequest()->getPost('start')));
            $end = new \Ilch\Date(trim($this->getRequest()->getPost('end')));
            $text = trim($this->getRequest()->getPost('text'));
            $color = trim($this->getRequest()->getPost('color'));
            
            if (empty($start)) {
                $this->addMessage('missingDate', 'danger');
            } elseif(empty($title)) {
                $this->addMessage('missingTitle', 'danger');
            } else {
                $calendarModel->setTitle($title);
                $calendarModel->setPlace($place);
                $calendarModel->setStart($start);
                $calendarModel->setEnd($end);
                $calendarModel->setText($text);
                $calendarModel->setColor($color);
                $calendarMapper->save($calendarModel);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(['action' => 'index']);
            }
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $calendarMapper = new CalendarMapper();
            $calendarMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
