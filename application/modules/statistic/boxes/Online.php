<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Boxes;

class Online extends \Ilch\Box
{
    public function render()
    {
        $statisticMapper = new \Modules\Statistic\Mappers\Statistic();
        
        $allCount = $statisticMapper->getVisitsCountOnline();
        $users = $statisticMapper->getVisitsOnlineUser();

        $this->getView()->set('usersOnline', $users);
        $this->getView()->set('guestOnline', $allCount - count($users));
    }
}
