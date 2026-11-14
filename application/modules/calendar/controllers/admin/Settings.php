<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Controllers\Admin;

use Ilch\Validation;
use Modules\Calendar\Mappers\Events as EventsMapper;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuCalendar',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $eventsMapper = new EventsMapper();
        // Exclude the default calendar module entry from the view as it should always be enabled.
        $events = $eventsMapper->getEntriesBy(['url !=' => 'calendar/events/index/']);

        $this->getView()->set('events', $events);
    }

    public function enableAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventsMapper = new EventsMapper();
            $event = $eventsMapper->getEntryById($this->getRequest()->getParam('id'));

            // Set value for enabled if it isn't the default calendar module entry.
            if ($event && $event->getUrl() !== 'calendar/events/index/') {
                $eventsMapper->setEnabled($this->getRequest()->getParam('id'), $this->getRequest()->getParam('enabled') === 'true');

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
        }

        $this->redirect()
            ->withMessage('calendarSourceSaveFailure', 'danger')
            ->to(['action' => 'index']);
    }
}
