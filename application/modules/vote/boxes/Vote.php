<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Boxes;

use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\User\Mappers\User as UserMapper;

class Vote extends \Ilch\Box
{
    public function render()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();
        $userMapper = new UserMapper();

        $readAccess = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
            if ($user) {
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }
        }

        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIP = $_SERVER['REMOTE_ADDR'];
        } else {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $this->getView()->set('resultMapper', $resultMapper)
            ->set('votes', $voteMapper->getVotes(['status' => 0], $readAccess))
            ->set('readAccess', $readAccess)
            ->set('clientIP', $clientIP);
    }
}
