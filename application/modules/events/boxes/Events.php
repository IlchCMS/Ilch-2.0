<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Boxes;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\User\Mappers\User as UserMapper;

class Events extends \Ilch\Box
{
    public function render()
    {
        $eventMapper = new EventMapper();
        $userMapper = new UserMapper;

        $config = \Ilch\Registry::get('config');

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

        $this->getView()->set('eventList', $eventMapper->getEventListUpcoming($config->get('event_box_event_limit')))
            ->set('readAccess', $readAccess);
    }
}
