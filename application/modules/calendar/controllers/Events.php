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

        $this->getView()->set('calendarList', $calendarMapper->getEntriesForJson($this->getRequest()->getQuery('start') ?? '', $this->getRequest()->getQuery('end') ?? '', $readAccess));
    }

    public function showAction()
    {
        $calendarMapper = new CalendarMapper();
        $userMapper = new UserMapper();

        $calendar = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));

        if ($calendar) {
            $user = null;
            $adminAccess = false;
            if ($this->getUser()) {
                $user = $userMapper->getUserById($this->getUser()->getId());
                $adminAccess = $this->getUser()->isAdmin();
            }

            $readAccess = [3];
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }

            if (!is_in_array($readAccess, explode(',', $calendar->getReadAccess())) or !$adminAccess) {
                $this->redirect()
                    ->withMessage('noCalendar', 'warning')
                    ->to(['controller' => 'index', 'action' => 'index']);

                return;
            }

            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuCalendar'))
                ->add($calendar->getTitle());
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index', 'action' => 'index'])
                ->add($calendar->getTitle(), ['controller' => 'events', 'action' => 'show', 'id' => $calendar->getId()]);

            $this->getView()->set('calendar', $calendar);
        } else {
            $this->redirect()
                ->withMessage('noCalendar', 'warning')
                ->to(['controller' => 'index', 'action' => 'index']);
        }
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

        $this->getView()->set('calendarList', $calendarMapper->getEntries(['ra.group_id' => $readAccess]));
    }
}
