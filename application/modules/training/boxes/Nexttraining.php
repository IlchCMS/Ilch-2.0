<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Boxes;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
use Modules\Training\Models\Training as TrainingModel;
use Modules\User\Mappers\User as UserMapper;

class Nexttraining extends \Ilch\Box
{
    public function render()
    {
        $trainingMapper = new TrainingMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        // Get trainings, calculate next date if it's a recurrent event and sort them by date.
        $trainings = $trainingMapper->getTrainingsListWithLimt($config->get('training_boxNexttrainingLimit') ?: 5, $groupIds);
        foreach ($trainings as $training) {
            $trainingMapper->calculateNextTrainingDate($training);
        }
        usort($trainings, fn(TrainingModel $a, TrainingModel $b) => strcmp($a->getDate(), $b->getDate()));

        $this->getView()->set('trainingMapper', $trainingMapper)
            ->set('entrantsMapper', $entrantsMapper)
            ->set('trainings', $trainings);
    }
}
