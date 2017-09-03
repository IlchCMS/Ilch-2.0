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

        $this->getView()->set('voteMapper', $voteMapper)
            ->set('resultMapper', $resultMapper)
            ->set('ipMapper', $ipMapper)
            ->set('userMapper', $userMapper)
            ->set('vote', $voteMapper->getVotes(['status' => 0]))
            ->set('readAccess', $readAccess);
    }
}
