<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Boxes;

use Ilch\Box;
use Ilch\Registry;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\User\Mappers\User as UserMapper;

class Lastwar extends Box
{
    public function render()
    {
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();
        $userMapper = new UserMapper();
        $config = Registry::get('config');

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

        $this->getView()->set('warMapper', $warMapper)
            ->set('gamesMapper', $gamesMapper)
            ->set('war', $warMapper->getWarListByStatusAndLimt(2, $config->get('war_boxLastWarLimit'), $readAccess, 'DESC'));
    }
}
