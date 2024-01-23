<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Boxes;

use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
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

        $this->getView()->set('trainingMapper', $trainingMapper)
            ->set('entrantsMapper', $entrantsMapper)
            ->set('training', $trainingMapper->getTrainingsListWithLimt($config->get('training_boxNexttrainingLimit') ?: 5, $groupIds));
    }
}
