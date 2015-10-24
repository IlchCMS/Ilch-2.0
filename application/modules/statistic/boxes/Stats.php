<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Boxes;

class Stats extends \Ilch\Box
{
    public function render()
    {
        $date = new \Ilch\Date();
        $statisticMapper = new \Modules\Statistic\Mappers\Statistic();
        $this->getView()->set('visitsToday', $statisticMapper->getVisitsCount($date->format('Y-m-d', true)));
        $this->getView()->set('visitsOnline', $statisticMapper->getVisitsCountOnline());
        $date->modify('-1 day');
        $this->getView()->set('visitsYesterday', $statisticMapper->getVisitsCount($date->format('Y-m-d', true)));
        $this->getView()->set('visitsMonth', $statisticMapper->getVisitsMonthCount());
        $this->getView()->set('visitsYear', $statisticMapper->getVisitsYearCount());
        $this->getView()->set('visitsRegistUser', $statisticMapper->getRegistUserCount());
        $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount());
    }
}
