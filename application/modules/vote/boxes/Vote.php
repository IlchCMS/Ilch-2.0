<?php
/**
 * @copyright Ilch 2.0
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

        $this->getView()->set('voteMapper', $voteMapper)
            ->set('resultMapper', $resultMapper)
            ->set('ipMapper', $ipMapper)
            ->set('userMapper', $userMapper)
            ->set('vote', $voteMapper->getVotes(['status' => 0]))
            ->set('readAccess', $readAccess);
    }
}
