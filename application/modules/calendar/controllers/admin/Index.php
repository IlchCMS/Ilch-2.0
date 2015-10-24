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
        $this->getLayout()->addMenu
                (
                'menuCalendar', array
            (
            array
                (
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
            ),
                )
        );

        $this->getLayout()->addMenuAction
                (
                array
                    (
                    'name' => 'add',
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), array('action' => 'index'));

        $calendarMapper = new CalendarMapper();

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $calendarId) {
                    $calendarMapper->delete($calendarId);
                }
            }
        }

        $calendar = $calendarMapper->getEntries();

        $this->getView()->set('calendar', $calendar);
    }

    public function treatAction()
    {
        $calendarMapper = new CalendarMapper();
        $calendarModel = new CalendarModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('calendar', $calendarMapper->getCalendarById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));            
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
                
                $this->redirect(array('action' => 'index'));
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

        $this->redirect(array('action' => 'index'));
    }
}
