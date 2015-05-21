<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Controllers;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $statisticMapper = new StatisticMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'));

        $date = new \Ilch\Date();
        $this->getView()->set('visitsToday', $statisticMapper->getVisitsCount($date->format('Y-m-d')));
        $this->getView()->set('visitsMonth', $statisticMapper->getVisitsMonthCount());
        $this->getView()->set('visitsYear', $statisticMapper->getVisitsYearCount());
        $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount());
        
        $this->getView()->set('statisticList', $statisticMapper->getVisitsDate());
        $this->getView()->set('statisticBrowserList', $statisticMapper->getVisitsBrowser());
        $this->getView()->set('statisticOSList', $statisticMapper->getVisitsOS());
    }

    public function onlineAction()
    {
        $statisticMapper = new StatisticMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuOnline'), array('action' => 'online'));

        $this->getView()->set('userOnlineList', $statisticMapper->getVisitsOnline());
    }
}
