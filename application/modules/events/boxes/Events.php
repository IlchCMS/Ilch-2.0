<?php
/**
 * @copyright Ilch 2.0
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

        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }
        $user = $userMapper->getUserById($userId);
        $ids = [3];
        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $us) {
                $ids[] = $us->getId();
            }
        }
        $readAccess = explode(',',implode(',', $ids));

        $this->getView()->set('eventList', $eventMapper->getEventListUpcoming($config->get('event_boxEventLimit')))
            ->set('readAccess', $readAccess);
    }
}
