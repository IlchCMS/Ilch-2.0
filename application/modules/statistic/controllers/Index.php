<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Controllers;

use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $statisticMapper = new StatisticMapper();
        $moduleMapper = new ModuleMapper();
        $date = new \Ilch\Date();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index']);

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

        $visibilitySettings = explode(',', $this->getConfig()->get('statistic_visibleStats'));
        $this->getView()->set('siteStatistic', $visibilitySettings[0]);
        $this->getView()->set('ilchVersionStatistic', $visibilitySettings[1]);
        $this->getView()->set('modulesStatistic', $visibilitySettings[2]);
        $this->getView()->set('visitsStatistic', $visibilitySettings[3]);
        $this->getView()->set('browserStatistic', $visibilitySettings[4]);
        $this->getView()->set('osStatistic', $visibilitySettings[5]);
    }

    public function showAction()
    {
        $statisticMapper = new StatisticMapper();

        $month = $this->getRequest()->getParam('month');
        $year = $this->getRequest()->getParam('year');
        $os = $this->getRequest()->getParam('os');
        $browser = $this->getRequest()->getParam('browser');

        // Early exit if browser or os was never seen before.
        // This avoids adding untrusted parts to menus and urls later.
        if (!empty($browser) && !$statisticMapper->browserSeenBefore($browser)) {
             $this->addMessage('unknownOrUnseenBrowser', 'danger');
             $this->redirect()
             ->to(['action' => 'index']);
        }
        if (!empty($os) && !$statisticMapper->osSeenBefore($os)) {
            $this->addMessage('unknownOrUnseenOS', 'danger');
            $this->redirect()
                ->to(['action' => 'index']);
        }
        if ($year == '') {
            $this->redirect()
                ->to(['action' => 'index']);
        }

        $dateformat = 'Y';
        $datestring = $year;
        if ($month != '') {
            $dateformat = 'Y-m';
            $datestring .= '-' . $month;
        }
        if (!validateDate($datestring, $dateformat)) {
            $this->addMessage('invalidDate', 'danger');
            $this->redirect()
                ->to(['action' => 'index']);
        }

        if ($os != '') {
            if ($month != '') {
                $date = new \Ilch\Date($year.'-'.$month.'-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans($date->format('F', true)), ['action' => 'show', 'year' => $year, 'month' => $month])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year])
                    ->add($os, ['action' => 'show', 'year' => $year, 'month' => $month, 'os' => $os]);
            } else {
                $date = new \Ilch\Date($year.'-01-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year])
                    ->add($os, ['action' => 'show', 'year' => $year, 'os' => $os]);
            }
        }

        if ($browser != '') {
            if ($month != '') {
                $date = new \Ilch\Date($year.'-'.$month.'-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans($date->format('F', true)), ['action' => 'show', 'year' => $year, 'month' => $month])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year])
                    ->add($browser, ['action' => 'show', 'year' => $year, 'month' => $month, 'browser' => $browser]);
            } elseif ($month == '') {
                $date = new \Ilch\Date($year.'-01-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year])
                    ->add($browser, ['action' => 'show', 'year' => $year, 'browser' => $browser]);
            }
        }

        if ($browser == '' && $os == '') {
            if ($month != '') {
                $date = new \Ilch\Date($year.'-'.$month.'-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans($date->format('F', true)), ['action' => 'show', 'year' => $year, 'month' => $month])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year]);
            } else {
                $date = new \Ilch\Date($year.'-01-01');

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                    ->add($date->format('Y', true), ['action' => 'show', 'year' => $year]);
            }
        }

        if ($month != '' && $year != '' && $os != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year, $month));
            $this->getView()->set('statisticOSVersionList', $statisticMapper->getVisitsOS($year, $month, $os));
        } elseif ($year != '' && $os != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year));
            $this->getView()->set('statisticOSVersionList', $statisticMapper->getVisitsOS($year, '', $os));
        } elseif ($month != '' && $year != '' && $browser != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year, $month));
            $this->getView()->set('statisticBrowserVersionList', $statisticMapper->getVisitsBrowser($year, $month, $browser));
        } elseif ($year != '' && $browser != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsCount('', $year));
            $this->getView()->set('statisticBrowserVersionList', $statisticMapper->getVisitsBrowser($year, '', $browser));
        } elseif ($year != '' && $month != '') {
            $this->getView()->set('visitsTotal', $statisticMapper->getVisitsMonthCount($year, $month));
            $this->getView()->set('statisticHourList', $statisticMapper->getVisitsHour($year, $month));
            $this->getView()->set('statisticDayList', $statisticMapper->getVisitsDay($year, $month));
            $this->getView()->set('statisticYearMonthDayList', $statisticMapper->getVisitsYearMonthDay($year, $month));
            $this->getView()->set('statisticYearList', $statisticMapper->getVisitsYear($year));
            $this->getView()->set('statisticBrowserList', $statisticMapper->getVisitsBrowser($year, $month));
            $this->getView()->set('statisticLanguageList', $statisticMapper->getVisitsLanguage($year, $month));
            $this->getView()->set('statisticOSList', $statisticMapper->getVisitsOS($year, $month));
        } elseif ($month == '' && $year != '') {
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
        $moduleMapper = new ModuleMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuStatistic'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuOnline'), ['action' => 'online']);

        $this->getView()->set('modulesMapper', $moduleMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('userOnlineList', $statisticMapper->getVisitsOnline());
    }
}
