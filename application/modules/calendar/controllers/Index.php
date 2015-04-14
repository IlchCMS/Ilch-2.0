<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Controllers;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), array('controller' => 'index'));

        if ($this->getRequest()->isPost()) {            
            $calendarModel = new EventModel();

            $calendarModel->setTitle($this->getRequest()->getPost('title'));
            $calendarModel->setStart($this->getRequest()->getPost('start'));
            $calendarModel->setEnd($this->getRequest()->getPost('end'));
            $calendarMapper->saveUserOnEvent($calendarModel);

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('calendarList', $calendarMapper->getEntries());
        $this->getView()->set('birthdayList', $userMapper->getUserList());
    }

    public function showAction()
    {
        $calendarMapper = new CalendarMapper();

        $calendar = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), array('controller' => 'index', 'action' => 'index'))
                ->add($calendar->getTitle(), array('controller' => 'index', 'action' => 'show', 'id' => $calendar->getId()));

        $this->getView()->set('calendar', $calendarMapper->getCalendarById($this->getRequest()->getParam('id')));
    }
}
