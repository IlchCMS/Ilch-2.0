<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Calendar\Mappers\Calendar as CalendarMapper;

class Trainings extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $trainingMapper = new TrainingMapper();
        $userMapper = new UserMapper();
        $calendarMapper = new CalendarMapper();
        $this->getLayout()->setFile('modules/calendar/layouts/events');
        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $this->getView()->set('trainingList', $trainingMapper->getTrainingsForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end'), $groupIds))
            ->set('calendarMapper', $calendarMapper);
    }
}
