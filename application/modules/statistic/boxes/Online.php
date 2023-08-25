<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Boxes;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;

class Online extends \Ilch\Box
{
    public function render()
    {
        $statisticMapper = new StatisticMapper();

        $usersOnline = $statisticMapper->getVisitsOnlineUser();

        $this->getView()->set('usersOnline', $usersOnline);
        $this->getView()->set('guestOnline', $statisticMapper->getVisitsCountOnline() - count($usersOnline));
    }
}
