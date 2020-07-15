<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\User\Mappers\User as UserMapper;

class Trainings extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $trainingMapper = new TrainingMapper();
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

        $this->getView()->set('trainingList', $trainingMapper->getTrainingsForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')))
            ->set('readAccess', $readAccess);
    }
}
