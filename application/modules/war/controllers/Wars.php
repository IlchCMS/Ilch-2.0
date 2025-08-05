<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers;

use Ilch\Controller\Frontend;
use Modules\War\Mappers\War as WarMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Wars extends Frontend
{
    public function indexAction()
    {
        $warMapper = new WarMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $warList = [];

        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $input = $this->getRequest()->getQuery();
        $validation = Validation::create(
            $input,
            [
                'start' => 'required|date:Y-m-d\TH\\:i\\:sP',
                'end'   => 'required|date:Y-m-d\TH\\:i\\:sP',
            ]
        );

        if ($validation->isValid()) {
            $warList = $warMapper->getWarsForJson($this->getRequest()->getQuery('start') ?? '', $this->getRequest()->getQuery('end') ?? '', $groupIds);
        }

        $this->getView()->set('warList', $warList);
    }
}
