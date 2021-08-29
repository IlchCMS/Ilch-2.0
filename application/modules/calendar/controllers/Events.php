<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Controllers;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\User\Mappers\User as UserMapper;

class Events extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('calendarList', $calendarMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')))
            ->set('readAccess', $readAccess);
    }

    public function showAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $adminAccess = false;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        $calendar = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuCalendar'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index', 'action' => 'index']);

        if (is_in_array($readAccess, explode(',', $calendar->getReadAccess())) or $adminAccess == true) {
            $this->getLayout()->getTitle()
                ->add($calendar->getTitle());
            $this->getLayout()->getHmenu()
                ->add($calendar->getTitle(), ['controller' => 'events', 'action' => 'show', 'id' => $calendar->getId()]);
        } else {
            $calendar = null;
        }

        $this->getView()->set('calendar', $calendar);
    }

    public function iCalAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getLayout()->setFile('modules/calendar/layouts/iCal');

        $this->getView()->set('calendarList', $calendarMapper->getEntries())
            ->set('readAccess', $readAccess);
    }
}
