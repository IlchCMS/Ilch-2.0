<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventsMapper;
use Modules\User\Mappers\User as UserMapper;

class Events extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventsMapper = new EventsMapper();
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

        $this->getView()->set('eventList', $eventsMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')))
            ->set('readAccess', $readAccess);
    }
}
