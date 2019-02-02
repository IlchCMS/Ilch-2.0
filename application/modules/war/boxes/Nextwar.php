<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Boxes;

use Modules\War\Mappers\War as WarMapper;
use Modules\User\Mappers\User as UserMapper;

class Nextwar extends \Ilch\Box
{
    public function render()
    {
        $warMapper = new WarMapper();
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

        $this->getView()->set('warMapper', $warMapper)
            ->set('date', $date->format(null, true))
            ->set('war', $warMapper->getWarListByStatusAndLimt(1, $config->get('war_boxNextWarLimit')))
            ->set('readAccess', $readAccess);
    }
}
