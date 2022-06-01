<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\War as WarMapper;
use Modules\User\Mappers\User as UserMapper;

class Wars extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $warMapper = new WarMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

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

        $this->getView()->set('warList', $warMapper->getWarsForJson($this->getRequest()->getQuery('start') ?? '', $this->getRequest()->getQuery('end') ?? '', $readAccess));
    }
}
