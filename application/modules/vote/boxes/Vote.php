<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Boxes;

use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\Vote\Mappers\Ip as IpMapper;
use Modules\User\Mappers\User as UserMapper;

class Vote extends \Ilch\Box
{
    public function render()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();
        $ipMapper = new IpMapper();
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

        $this->getView()->set('voteMapper', $voteMapper)
            ->set('resultMapper', $resultMapper)
            ->set('ipMapper', $ipMapper)
            ->set('userMapper', $userMapper)
            ->set('votes', $voteMapper->getVotes(['status' => 0], $readAccess))
            ->set('readAccess', $readAccess);
    }
}
