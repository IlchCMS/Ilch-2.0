<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Boxes;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;

class Online extends \Ilch\Box
{
    public function render()
    {
        $statisticMapper = new StatisticMapper();

        $this->getView()->set('usersOnline', $statisticMapper->getVisitsOnlineUser());
        $this->getView()->set('guestOnline', $statisticMapper->getVisitsCountOnline() - count($statisticMapper->getVisitsOnlineUser()));
    }
}
