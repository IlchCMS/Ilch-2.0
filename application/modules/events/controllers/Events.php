<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Ilch\Validation;
use Modules\Events\Mappers\Events as EventsMapper;
use Modules\User\Mappers\User as UserMapper;

class Events extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventsMapper = new EventsMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $eventList = [];

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

        $input = $this->getRequest()->getQuery();
        $validation = Validation::create(
            $input,
            [
                'start' => 'required|date:Y-m-d\TH\\:i\\:sP',
                'end'   => 'required|date:Y-m-d\TH\\:i\\:sP',
            ]
        );

        if ($validation->isValid()) {
            $eventList = $eventsMapper->getEntriesForJson($input['start'], $input['end']);
        }

        $this->getView()->set('eventList', $eventList)
            ->set('readAccess', $readAccess);
    }
}
