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
        $date = new \Ilch\Date();
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

        $this->getView()->set('trainingMapper', $trainingMapper)
            ->set('entrantsMapper', $entrantsMapper)
            ->set('date', $date->format(null, true))
            ->set('training', $trainingMapper->getTrainingsListWithLimt($config->get('training_boxNexttrainingLimit')?$config->get('training_boxNexttrainingLimit'):5))
            ->set('readAccess', $readAccess);
    }
}
