<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Controllers;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Admin\Mappers\Module as ModuleMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $statisticMapper = new StatisticMapper();
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'));

        $date = new \Ilch\Date();
        $this->getView()->set('dateCmsInstalled', $this->getConfig()->get('date_cms_installed'));
        $this->getView()->set('registUserCount', $statisticMapper->getRegistUserCount());
        $this->getView()->set('registNewUser', $statisticMapper->getRegistNewUser());
        $this->getView()->set('articlesCount', $statisticMapper->getArticlesCount());
        $this->getView()->set('commentsCount', $statisticMapper->getCommentsCount());
        $this->getView()->set('modulesCount', $statisticMapper->getModulesCount());
        $this->getView()->set('CMSVersion', $this->getConfig()->get('version'));

        $this->getView()->set('modules', $moduleMapper->getModules());

        $this->getView()->set('visitsToday', $statisticMapper->getVisitsCount($date->format('Y-m-d')));
        $this->getView()->set('visitsMonth', $statisticMapper->getVisitsMonthCount());
        $this->getView()->set('visitsYear', $statisticMapper->getVisitsYearCount());
        $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount($date->format('Y-m-d')));
        $this->getView()->set('visitsYearTotal', $statisticMapper->getVisitsCount('', $date->format('Y')));
        $this->getView()->set('visitsAllTotal', $statisticMapper->getVisitsCount());

        $this->getView()->set('statisticHourList', $statisticMapper->getVisitsHour($date->format('Y', true)));
        $this->getView()->set('statisticDayList', $statisticMapper->getVisitsDay($date->format('Y', true)));
        $this->getView()->set('statisticYearMonthDayList', $statisticMapper->getVisitsYearMonthDay($date->format('Y', true), $date->format('m', true)));
        $this->getView()->set('statisticYearMonthList', $statisticMapper->getVisitsYearMonth());
        $this->getView()->set('statisticYearList', $statisticMapper->getVisitsYear());
        $this->getView()->set('statisticBrowserList', $statisticMapper->getVisitsBrowser($date->format('Y', true)));
        $this->getView()->set('statisticLanguageList', $statisticMapper->getVisitsLanguage($date->format('Y', true)));
        $this->getView()->set('statisticOSList', $statisticMapper->getVisitsOS($date->format('Y', true)));
    }

    public function showAction()
    {
        $statisticMapper = new StatisticMapper();

        $month = $this->getRequest()->getParam('month');
        $year = $this->getRequest()->getParam('year');
        $os = $this->getRequest()->getParam('os');
        $browser = $this->getRequest()->getParam('browser');

        if ($year != '' AND $month != '' AND $os != '') {
            $date = new \Ilch\Date($year.'-'.$month.'-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('F', true), array('action' => 'show', 'year' => $year, 'month' => $month))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year))
                    ->add($os, array('action' => 'show', 'year' => $year, 'month' => $month, 'os' => $os));
        }
        if ($year != '' AND $os != '') {
            $date = new \Ilch\Date($year.'-01-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year))
                    ->add($os, array('action' => 'show', 'year' => $year, 'os' => $os));
        }
        if ($year != '' AND $month != '' AND $browser != '') {
            $date = new \Ilch\Date($year.'-'.$month.'-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('F', true), array('action' => 'show', 'year' => $year, 'month' => $month))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year))
                    ->add($browser, array('action' => 'show', 'year' => $year, 'month' => $month, 'browser' => $browser));
        }
        if ($month == '' AND $year != '' AND $browser != '') {
            $date = new \Ilch\Date($year.'-01-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year))
                    ->add($browser, array('action' => 'show', 'year' => $year, 'browser' => $browser));
        }
        if ($year != '' AND $month != '') {
            $date = new \Ilch\Date($year.'-'.$month.'-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('F', true), array('action' => 'show', 'year' => $year, 'month' => $month))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year));
        }
        if ($year != '') {
            $date = new \Ilch\Date($year.'-01-01');

            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), array('action' => 'index'))
                    ->add($date->format('Y', true), array('action' => 'show', 'year' => $year));
        }

        if ($month != '' AND $year != '' AND $os != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year, $month));
            $this->getView()->set('statisticOSVersionList', $statisticMapper->getVisitsOS($year, $month, $os));
        } elseif ($year != '' AND $os != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year));
            $this->getView()->set('statisticOSVersionList', $statisticMapper->getVisitsOS($year, '', $os));
        } elseif ($month != '' AND $year != '' AND $browser != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year, $month));
            $this->getView()->set('statisticBrowserVersionList', $statisticMapper->getVisitsBrowser($year, $month, $browser));
        } elseif ($year != '' AND $browser != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year));
            $this->getView()->set('statisticBrowserVersionList', $statisticMapper->getVisitsBrowser($year, '', $browser));
        } elseif ($year != '' AND $month != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsMonthCount($year, $month));
            $this->getView()->set('statisticHourList', $statisticMapper->getVisitsHour($year, $month));
            $this->getView()->set('statisticDayList', $statisticMapper->getVisitsDay($year, $month));
            $this->getView()->set('statisticYearMonthDayList', $statisticMapper->getVisitsYearMonthDay($year, $month));
            $this->getView()->set('statisticYearList', $statisticMapper->getVisitsYear($year));
            $this->getView()->set('statisticBrowserList', $statisticMapper->getVisitsBrowser($year, $month));
            $this->getView()->set('statisticLanguageList', $statisticMapper->getVisitsLanguage($year, $month));
            $this->getView()->set('statisticOSList', $statisticMapper->getVisitsOS($year, $month));
        } elseif ($month == '' AND $year != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year));
            $this->getView()->set('statisticHourList', $statisticMapper->getVisitsHour($year));
            $this->getView()->set('statisticDayList', $statisticMapper->getVisitsDay($year));
            $this->getView()->set('statisticYearMonthList', $statisticMapper->getVisitsYearMonth($year));
            $this->getView()->set('statisticYearList', $statisticMapper->getVisitsYear($year));
            $this->getView()->set('statisticBrowserList', $statisticMapper->getVisitsBrowser($year));
            $this->getView()->set('statisticLanguageList', $statisticMapper->getVisitsLanguage($year));
            $this->getView()->set('statisticOSList', $statisticMapper->getVisitsOS($year));
            $this->getView()->set('statisticOSVersionList', $statisticMapper->getVisitsOS($year, $month, $os));
        }
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
