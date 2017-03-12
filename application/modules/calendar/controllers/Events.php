<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Controllers;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;

class Events extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $this->getView()->set('calendarList', $calendarMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')));
    }

    public function showAction()
    {
        $calendarMapper = new CalendarMapper();

        $calendar = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuCalendar'))
            ->add($calendar->getTitle());
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index', 'action' => 'index'])
            ->add($calendar->getTitle(), ['controller' => 'events', 'action' => 'show', 'id' => $calendar->getId()]);

        $this->getView()->set('calendar', $calendarMapper->getCalendarById($this->getRequest()->getParam('id')));
    }

    public function iCalAction()
    {
        $calendarMapper = new CalendarMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/iCal');

        $this->getView()->set('calendarList', $calendarMapper->getEntries());
    }
}
