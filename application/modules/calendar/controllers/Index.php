<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Controllers;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Events\Mappers\Events as EventsMapper;
use Modules\Away\Mappers\Away as AwayMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();
        $eventsMapper = new EventsMapper();
        $awayMapper = new AwayMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index']);

        $this->getView()->set('calendarList', $calendarMapper->getEntries());
        $this->getView()->set('birthdayList', $userMapper->getUserList());

        if ($calendarMapper->existsTable('away') == true) {
            $this->getView()->set('awayList', $awayMapper->getAway(['show' => 1]));
        }
        if ($calendarMapper->existsTable('events') == true) {
            $this->getView()->set('eventList', $eventsMapper->getEntries(['show' => 1]));
        }
    }

    public function showAction()
    {
        $calendarMapper = new CalendarMapper();

        $calendar = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index', 'action' => 'index'])
                ->add($calendar->getTitle(), ['controller' => 'index', 'action' => 'show', 'id' => $calendar->getId()]);

        $this->getView()->set('calendar', $calendarMapper->getCalendarById($this->getRequest()->getParam('id')));
    }
}
