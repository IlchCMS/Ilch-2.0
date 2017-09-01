<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Vote\Boxes;

use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\User\Mappers\User as UserMapper;

class Vote extends \Ilch\Box
{
    public function render()
    {
        $voteMapper = new VoteMapper();
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

        $this->getView()->set('vote', $voteMapper->getVotes(['status' => 0]))
            ->set('readAccess', $readAccess);
    }
}
