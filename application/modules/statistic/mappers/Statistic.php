<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Mappers;

use Modules\Statistic\Models\Statistic as StatisticModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\User\Models\User;
use Ilch\Database\Exception;

class Statistic extends \Ilch\Mapper
{
    /**
     * Period start for "all time" queries. Matches the aggregation frontier
     * epoch, so tiered reads can use it as a lower bound.
     */
    private const ALL_TIME_START = '1000-01-01';

    /**
     * Aggregation frontiers ('Y-m-d' per level), cached per mapper instance.
     *
     * @var array{daily: string, monthly: string, yearly: string}|null
     */
    private ?array $frontierCache = null;

    /**
     * Returns all online users.
     *
     * @return array []|User[]
     * @throws Exception
     */
    public function getVisitsOnlineUser(): array
    {
        $userMapper = new UserMapper();
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $select = $this->db()->select(['date_last_activity', 'user_id'])
            ->from('visits_online')
            ->where(['date_last_activity >' => $date->format('Y-m-d H:i:s', true), 'user_id >' => 0]);
        $rows = $select->execute()->fetchRows();

        $users = [];
        foreach ($rows as $row) {
            if ($row['user_id'] && ($user = $userMapper->getUserById($row['user_id'])) !== null) {
                $users[] = $user;
            }
        }

        return $users;
    }

    /**
     * Returns all online visits.
     *
     * @return null|StatisticModel[]
     */
    public function getVisitsOnline(): ?array
    {
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $select = $this->db()->select(['*'])
            ->from('visits_online')
            ->where(['date_last_activity >' => $date->format('Y-m-d H:i:s', true)])
            ->order(['date_last_activity' => 'DESC']);

        $entryArray = $select->execute()->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setByArray($entries);

            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns all users who were online.
     *
     * Stays on the raw table (identity data); the day range predicate is
     * served by idx_date.
     *
     * @return User[]
     * @since 2.1.20
     */
    public function getWhoWasOnline(): array
    {
        $userMapper = new UserMapper();

        // "Today" in the site-local timezone (same clock saveVisit() writes
        // with), expressed as a range predicate for idx_date.
        $today = (new \Ilch\Date())->format('Y-m-d', true);
        $next = $this->aggregateMapper()->addDays($today, 1);

        $sql = 'SELECT `[prefix]_visits_stats`.user_id, `[prefix]_visits_stats`.date, `[prefix]_users`.*
                FROM `[prefix]_visits_stats`
                INNER JOIN `[prefix]_users` ON user_id = `[prefix]_users`.id
                WHERE `[prefix]_visits_stats`.`date` >= "' . $today . ' 00:00:00"
                  AND `[prefix]_visits_stats`.`date` < "' . $next . ' 00:00:00"
                  AND `user_id` > 0
                GROUP BY `user_id`';

        $rows = $this->db()->queryArray($sql);

        $users = [];
        foreach ($rows as $row) {
            $users[] = $userMapper->loadFromArray($row);
        }

        return $users;
    }

    /**
     * Returns the visit count per hour of the given month/year.
     *
     * Stays on the raw table: hour is not part of the aggregate grain. The
     * range predicate is served by idx_date; retention bounds the raw
     * history depth.
     *
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsHour(?int $year = null, ?int $month = null): ?array
    {
        $sql = 'SELECT
                HOUR(`date`) AS `date_hour`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`' . $this->rawPeriodWhere($year, $month) . '
                GROUP BY HOUR(`date`)
                ORDER BY `date_hour` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date_hour']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the visit count per weekday of the given month/year.
     *
     * Tiered like the other reads: the daily aggregate table serves every
     * fully aggregated day (weekday is a function of the day, so the daily
     * grain is sufficient — no schema change), the raw table serves the
     * still-open recent tail (idx_date).
     *
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsDay(?int $year = null, ?int $month = null): ?array
    {
        [$start, $end] = $this->resolvePeriodRange($year, $month);

        $dailyFrontier = $this->frontier('daily');

        $rows = [];
        $tierEnd = min($end, $dailyFrontier);
        if ($start < $tierEnd) {
            $rows = $this->mergeWeekdayRows($rows, $this->aggregateWeekdayRows($start, $tierEnd));
        }

        $rawStart = max($start, $dailyFrontier);
        if ($rawStart < $end) {
            $rows = $this->mergeWeekdayRows($rows, $this->rawWeekdayRows($rawStart, $end));
        }

        if (empty($rows)) {
            return null;
        }

        ksort($rows); // Monday (0) .. Sunday (6), same order as the old ORDER BY date_week ASC

        $entry = [];
        foreach ($rows as $data) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($data['visits']);
            $statisticModel->setDate($data['date_full']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the per-day visit totals of one calendar month.
     *
     * Reads the daily aggregate table for fully processed days and the raw
     * table (idx_date) for the still-open recent window, so recent days stay
     * exact between aggregation runs.
     *
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsYearMonthDay(?int $year = null, ?int $month = null): ?array
    {
        [$start, $end] = $this->resolveMonthRange($year, $month);

        $dailyFrontier = $this->frontier('daily');

        $rows = [];
        $tierEnd = min($end, $dailyFrontier);
        if ($start < $tierEnd) {
            $rows = $this->mergeDimRows($rows, $this->aggregateDimRows('visits_stats_daily', 'date', ['`date`'], '`date`', $start, $tierEnd));
        }

        $rawStart = max($start, $dailyFrontier);
        if ($rawStart < $end) {
            $rows = $this->mergeDimRows($rows, $this->rawDimRows(['DATE(`date`)'], 'DATE(`date`)', $rawStart, $end));
        }

        if (empty($rows)) {
            return null;
        }

        krsort($rows); // newest day first

        $entry = [];
        foreach ($rows as $date => $visits) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setDate($date);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the per-month visit totals of one calendar year.
     *
     * A calendar year holds at most 365 daily aggregate days, so the daily
     * table covers the whole processed prefix; only the still-open recent
     * window is read from the raw table (idx_date).
     *
     * @param int|null $year
     * @return null|StatisticModel[]
     */
    public function getVisitsYearMonth(?int $year = null): ?array
    {
        [$start, $end] = $this->resolveYearRange($year);

        $dailyFrontier = $this->frontier('daily');
        $monthKey = "DATE_FORMAT(`date`, '%Y-%m-01')";

        $rows = [];
        $tierEnd = min($end, $dailyFrontier);
        if ($start < $tierEnd) {
            $rows = $this->mergeDimRows($rows, $this->aggregateDimRows('visits_stats_daily', 'date', [$monthKey], $monthKey, $start, $tierEnd));
        }

        $rawStart = max($start, $dailyFrontier);
        if ($rawStart < $end) {
            $rows = $this->mergeDimRows($rows, $this->rawDimRows([$monthKey], $monthKey, $rawStart, $end));
        }

        if (empty($rows)) {
            return null;
        }

        krsort($rows); // newest month first

        $entry = [];
        foreach ($rows as $month => $visits) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setDate($month);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the visit totals per year (one year when given, all years
     * otherwise).
     *
     * Complete years are read straight from the yearly aggregate table; the
     * still-open years (between the yearly frontier and the current year)
     * are computed by the tiered total.
     *
     * @param int|null $year
     * @return null|StatisticModel[]
     */
    public function getVisitsYear(?int $year = null): ?array
    {
        if ($year !== null && (int)$year > 0) {
            $year = (int)$year;
            $visits = $this->sumRange($year . '-01-01', ($year + 1) . '-01-01');
            if ($visits <= 0) {
                return null;
            }

            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setDate($year . '-01-01');

            return [$statisticModel];
        }

        $yearlyFrontier = $this->frontier('yearly');

        $rows = $this->aggregateYearRows(self::ALL_TIME_START, $yearlyFrontier);

        $currentYear = (int)substr($this->siteToday(), 0, 4);
        $firstIncompleteYear = (int)substr($yearlyFrontier, 0, 4);
        $firstDataYear = $this->firstDataYear();
        if ($firstDataYear !== null) {
            // Only years that can hold data (normally one or two iterations).
            for ($y = max($firstIncompleteYear, $firstDataYear); $y <= $currentYear; $y++) {
                $visits = $this->sumRange($y . '-01-01', ($y + 1) . '-01-01');
                if ($visits > 0) {
                    $rows[(string)$y] = $visits;
                }
            }
        }

        if (empty($rows)) {
            return null;
        }

        krsort($rows); // newest year first

        $entry = [];
        foreach ($rows as $yearKey => $visits) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setDate($yearKey . '-01-01');
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the visit totals per browser for a month, year or all time.
     *
     * Grouped from the aggregate tables (yearly / monthly / daily tiers),
     * with an indexed raw tail for the unprocessed recent window.
     *
     * @param int|null $year
     * @param int|null $month
     * @param string|null $browser
     * @return null|StatisticModel[]
     */
    public function getVisitsBrowser(?int $year = null, ?int $month = null, ?string $browser = null): ?array
    {
        [$start, $end] = $this->resolvePeriodRange($year, $month);

        $filter = '';
        if ($browser !== null && $browser !== '') {
            $filter = '`browser` = "' . $this->db()->escape($browser) . '"';
        }

        $rows = $this->visitsByDimension(['`browser`'], '`browser`', $start, $end, $filter);

        if (empty($rows)) {
            return null;
        }

        arsort($rows);

        $entry = [];
        foreach ($rows as $browserName => $visits) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setBrowser($browserName);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the visit totals per language for a month, year or all time.
     *
     * Grouped from the aggregate tables (yearly / monthly / daily tiers),
     * with an indexed raw tail for the unprocessed recent window.
     *
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsLanguage(?int $year = null, ?int $month = null): ?array
    {
        [$start, $end] = $this->resolvePeriodRange($year, $month);

        $rows = $this->visitsByDimension(['`lang`'], '`lang`', $start, $end);

        if (empty($rows)) {
            return null;
        }

        arsort($rows);

        $entry = [];
        foreach ($rows as $lang => $visits) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setLang($lang);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * Returns the visit totals per OS (with version) for a month, year or
     * all time.
     *
     * Grouped from the aggregate tables (yearly / monthly / daily tiers),
     * with an indexed raw tail for the unprocessed recent window.
     *
     * @param int|null $year
     * @param int|null $month
     * @param string|null $os
     * @return null|StatisticModel[]
     */
    public function getVisitsOS(?int $year = null, ?int $month = null, ?string $os = null): ?array
    {
        [$start, $end] = $this->resolvePeriodRange($year, $month);

        $filter = '';
        if ($os !== null && $os !== '') {
            $filter = '`os` = "' . $this->db()->escape($os) . '"';
        }

        $rows = $this->visitsByDimension(
            ['`os`', '`os_version`'],
            'CONCAT(`os`, CHAR(31), `os_version`)',
            $start,
            $end,
            $filter
        );

        if (empty($rows)) {
            return null;
        }

        arsort($rows);

        $entry = [];
        foreach ($rows as $key => $visits) {
            $parts = explode(chr(31), $key, 2);

            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($visits);
            $statisticModel->setOS($parts[0]);
            $statisticModel->setOSVersion($parts[1] ?? '');
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @return int
     */
    public function getVisitsCountOnline(): int
    {
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $select = $this->db()->select('COUNT(*)')
            ->from('visits_online')
            ->where(['date_last_activity >' => $date->format('Y-m-d H:i:s', true)]);

        return $select->execute()->fetchCell();
    }

    /**
     * @param array|string $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @return int
     */
    public function getArticlesCount(array|string $groupIds = '3', string $locale = ''): int
    {
        $articleMapper = new ArticleMapper();
        $articles = $articleMapper->getArticlesByAccess($groupIds, $locale);

        if ($articles) {
            return count($articles);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        $select = $this->db()->select('COUNT(*)')
            ->from('comments');

        return $select->execute()->fetchCell();
    }

    /**
     * @return int
     */
    public function getModulesCount(): int
    {
        $select = $this->db()->select('COUNT(*)')
            ->from('modules')
            ->where(['system' => '0']);

        return $select->execute()->fetchCell();
    }

    /**
     * @return int
     */
    public function getRegistUserCount(): int
    {
        $select = $this->db()->select('COUNT(*)')
            ->from('users')
            ->where(['confirmed' => '1']);

        return $select->execute()->fetchCell();
    }

    /**
     * @return int
     */
    public function getRegistNewUser(): int
    {
        $select = $this->db()->select('MAX(`id`)')
            ->from('users')
            ->where(['confirmed' => '1', 'locked' => '0']);

        return $select->execute()->fetchCell();
    }

    /**
     * Total visits for a day, a month, a year, or all time.
     *
     * Composed from the aggregate tables where the period is fully
     * processed and from an indexed raw count for the unprocessed recent
     * tail (see sumRange()).
     *
     * @param string|null $date
     * @param int|null $year
     * @param int|null $month
     * @return int
     */
    public function getVisitsCount(?string $date = null, ?int $year = null, ?int $month = null): int
    {
        $ag = $this->aggregateMapper();

        if ($month !== null && $year !== null && (int)$year > 0 && (int)$month > 0) {
            $start = $this->monthStart((int)$year, (int)$month);
            return $this->sumRange($start, $ag->addMonths($start, 1));
        }

        if ($year !== null && (int)$year > 0) {
            $year = (int)$year;
            return $this->sumRange($year . '-01-01', ($year + 1) . '-01-01');
        }

        if ($date !== null && $date !== '') {
            $start = $this->assertDateLiteral($date);
            return $this->sumRange($start, $ag->addDays($start, 1));
        }

        return $this->sumRange(self::ALL_TIME_START, $ag->addDays($ag->siteToday(), 1));
    }

    /**
     * Total visits of the given month, or of the current month.
     *
     * @param int|null $year
     * @param int|null $month
     * @return int
     */
    public function getVisitsMonthCount(?int $year = null, ?int $month = null): int
    {
        $ag = $this->aggregateMapper();

        if ($year !== null && $month !== null && (int)$year > 0 && (int)$month > 0) {
            $start = $this->monthStart((int)$year, (int)$month);
        } else {
            // "This month" in the site-local timezone (same clock saveVisit()
            // writes with).
            $start = $ag->firstOfMonth($ag->siteToday());
        }

        return $this->sumRange($start, $ag->addMonths($start, 1));
    }

    /**
     * Total visits of the current year.
     *
     * @return int
     */
    public function getVisitsYearCount(): int
    {
        // "This year" in the site-local timezone (same clock saveVisit()
        // writes with).
        $year = (int)substr($this->siteToday(), 0, 4);

        return $this->sumRange($year . '-01-01', ($year + 1) . '-01-01');
    }

    /**
     * @param int $count
     * @param int $totalcount
     * @return float
     */
    public function getPercent(int $count, int $totalcount): float
    {
        if ($totalcount <= 0) {
            return 0.0;
        }

        return round(($count / $totalcount) * 100);
    }

    /**
     * Get the name and/or version of the operating system from the user agent.
     *
     * @param string|null $name
     * @param string|null $version
     * @return string
     */
    public function getOS(?string $name = null, ?string $version = null): string
    {
        if (ISHTTPSPAGE && isset($_SERVER['HTTP_SEC_CH_UA']) && isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM'])) {
            if ($name != null) {
                if (!empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM'])) {
                    $osArray = [
                        '=Windows=' => 'Windows',
                        '=Android=' => 'Android',
                        '=Linux=' => 'Linux',
                        '=Chrome OS=' => 'Chrome OS',
                        '=iOS=' => 'iOS',
                        '=macOS=' => 'Mac OS',
                        '=Unknown=' => 'Unknown'
                    ];

                    foreach ($osArray as $regex => $os) {
                        if (preg_match($regex, $_SERVER['HTTP_SEC_CH_UA_PLATFORM'])) {
                            return $os;
                        }
                    }
                }
            } elseif ($version != null) {
                if (!empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION'])) {
                    return str_replace('"', '', $_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION']);
                }
            }
        } else {
            if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                return '';
            }

            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $osArray = [];

            if ($name != null) {
                $osArray = [
                    '=Windows NT|Windows Server 2003|Windows XP x64|Windows 98|Windows Phone|Windows 95=' => 'Windows',
                    '=Android=' => 'Android',
                    '=Linux|Ubuntu|X11=' => 'Linux',
                    '=SunOS=' => 'SunOs',
                    '=iPhone=' => 'iPhone',
                    '=iPad=' => 'iPad',
                    '=Mac OS X=' => 'Mac OS X',
                    '=Mac OS=' => 'Mac OS',
                    '=Mac_PowerPC|Macintosh=' => 'Macintosh'
                ];
            } elseif ($version != null) {
                $osArray = [
                    '=Android 14.0=' => '14.0',
                    '=Android 13.0=' => '13.0',
                    '=Android 12.0=' => '12.0',
                    '=Android 11.0=' => '11.0',
                    '=Android 10.0=' => '10.0',
                    '=Android 9.0=' => '9.0',
                    '=Android 8.1=' => '8.1',
                    '=Android 8.0=' => '8.0',
                    '=Android 7=' => '7.x',
                    '=Android 6=' => '6.x',
                    '=Android 5=' => '5.x',
                    '=Android 4.4=' => '4.4',
                    '=Android 4.1|Android 4.2|Android 4.3=' => '4.x',
                    '=Android 4.0=' => '4.0',
                    '=Android 3=' => '3.x',
                    '=Android 2.3=' => '2.3',
                    '=Android 2.2=' => '2.2',

                    '=Windows NT 11.0|Windows 11=' => '11',
                    '=Windows NT 10.0|Windows 10=' => '10',
                    '=Windows NT 6.3|Windows 8.1=' => '8.1',
                    '=Windows NT 6.2|Windows 8=' => '8',
                    '=Windows NT 6.1|Windows 7=' => '7',
                    '=Windows NT 6.0|Windows Vista=' => 'Vista',
                    '=Windows NT 5.1|Windows XP=' => 'XP',
                    '=Windows NT 5.0|Windows 2000=' => '2000',
                    '=Windows NT 5\.2|Windows Server 2003|Windows XP x64=' => 'Server 2003',
                    '=Windows NT 4|WinNT4=' => 'NT',
                    '=Windows 98=' => '98',

                    '=Windows Phone OS 7=' => 'Phone 7.x',
                    '=Windows Phone OS 8=' => 'Phone 8.0',
                    '=Windows Phone OS 8.1=' => 'Phone 8.1',
                    '=Windows Phone OS 10=' => 'Phone 10 Mobile',

                    '=Mac OS X 14|Mac OS X 14=' => '14',
                    '=Mac OS X 13|Mac OS X 13=' => '13',
                    '=Mac OS X 12.0|Mac OS X 12_0=' => '12.0',
                    '=Mac OS X 11.6|Mac OS X 11_6=' => '11.0',
                    '=Mac OS X 10.17|Mac OS X 10_17=' => '10.17',
                    '=Mac OS X 10.15|Mac OS X 10_15=' => '10.15',
                    '=Mac OS X 10.14|Mac OS X 10_14=' => '10.14',
                    '=Mac OS X 10.13|Mac OS X 10_13=' => '10.13',
                    '=Mac OS X 10.12|Mac OS X 10_12=' => '10.12',
                    '=Mac OS X 10.11|Mac OS X 10_11=' => '10.11',
                    '=Mac OS X 10.10|Mac OS X 10_10=' => '10.10',
                    '=Mac OS X 10.9|Mac OS X 10_9=' => '10.9',
                    '=Mac OS X 10.8|Mac OS X 10_8=' => '10.8',
                ];
            }

            foreach ($osArray as $regex => $os) {
                if (preg_match($regex, $useragent)) {
                    return $os;
                }
            }
        }

        return '';
    }

    /**
     * Detect which browser is used.
     *
     * @param string|null $version
     * @return string
     */
    public function getBrowser(?string $version = null): string
    {
        if (ISHTTPSPAGE && isset($_SERVER['HTTP_SEC_CH_UA'])) {
            $browserArray = [
                '=Google Chrome\";v\=\"([\.a-zA-Z0-9]*)=' => 'Chrome',
                '=Microsoft Edge\";v\=\"([\.a-zA-Z0-9]*)=' => 'Edge',
                '=Opera\";v\=\"([\.a-zA-Z0-9]*)=' => 'Opera',
                '=Firefox\";v\=\"([\.a-zA-Z0-9]*)=' => 'Firefox',
                '=Safari\";v\=\"([\.a-zA-Z0-9]*)=' => 'Safari',
            ];

            foreach ($browserArray as $regex => $name) {
                if (preg_match($regex, $_SERVER['HTTP_SEC_CH_UA'], $browser)) {
                    if ($version != null) {
                        return $name;
                    }
                    return $browser[1];
                }
            }
        } else {
            if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                return '';
            }

            $useragent = $_SERVER['HTTP_USER_AGENT'];

            if ($version != null) {
                if (preg_match('=Firefox/([\.a-zA-Z0-9]*)=', $useragent)) {
                    return 'Firefox';
                }
                if (preg_match('=MSIE ([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Internet Explorer';
                }
                if (preg_match('=rv:([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Internet Explorer';
                }
                if (preg_match('=Opera[/ ]([0-9\.]+)=', $useragent)) {
                    return 'Opera';
                }
                if (preg_match('=OPR\/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Opera';
                }
                if (preg_match('=Edge/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Edge';
                }
                if (preg_match('=Edg/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Edge';
                }
                if (preg_match('=Vivaldi\/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Vivaldi';
                }
                if (preg_match('=SamsungBrowser\/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Samsung Browser';
                }
                if (preg_match('=Chrome/([0-9]{1,3})\.[0-9]{1,3}=', $useragent)) {
                    return 'Chrome';
                }
                if (str_contains($useragent, 'Safari/')) {
                    return 'Safari';
                }
                if (str_contains($useragent, 'Konqueror')) {
                    return 'Konqueror';
                }
                if (preg_match('=Netscape|Navigator=', $useragent)) {
                    return 'Netscape';
                }

                return '';
            }

            if (preg_match('=Firefox/([\.a-zA-Z0-9]*)=', $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match('=MSIE ([0-9]{1,3}\.[0-9]{1,3})=', $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match('=rv:([0-9]{1,3}\.[0-9]{1,3})=', $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match('=Opera[/ ]([0-9\.]+)=', $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match('=OPR\/([0-9.]+)=', $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match('=Edge/([0-9.]+)=', $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match('=Edg/([0-9.]+)=', $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match('=Vivaldi\/([0-9.]+)=', $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match('=Chrome/([0-9.]+)=', $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (str_contains($useragent, 'Safari/')) {
                if (preg_match('=Version/([\.0-9]*)=', $useragent, $browser)) {
                    return $browser[1];
                } else {
                    return '';
                }
            }
        }

        return '';
    }

    /**
     * Save visit to database.
     *
     * @param array $row
     */
    public function saveVisit(array $row)
    {
        $date = new \Ilch\Date();
        $visitId = (int) $this->db()->select('id')
            ->from('visits_online')
            ->where(['user_id >' => 0, 'user_id' => $row['user_id']])
            ->orWhere(['session_id' => $row['session_id']])
            ->execute()
            ->fetchCell();

        if ($visitId) {
            $this->db()->update('visits_online')
                ->values(['user_id' => $row['user_id'], 'session_id' => $row['session_id'], 'site' => $row['site'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'lang' => $row['lang'], 'date_last_activity' => $date->format('Y-m-d H:i:s', true)])
                ->where(['id' => $visitId])
                ->execute();

            if ($row['user_id']) {
                $this->db()->update('users')
                    ->values(['date_last_activity' => $date->format('Y-m-d H:i:s', true)])
                    ->where(['id' => $row['user_id']])
                    ->execute();
            }
        } else {
            $this->db()->insert('visits_online')
                ->values(['user_id' => $row['user_id'], 'session_id' => $row['session_id'], 'site' => $row['site'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'ip_address' => $row['ip'], 'lang' => $row['lang'], 'date_last_activity' => $date->format('Y-m-d H:i:s', true)])
                ->execute();
        }

        // Delete "temporary" row of user being online as a guest before logging in.
        // This is the case when the user was logged in before (so there is a row with his user id),
        // but didn't log out or not using remember me, returned as guest with different session_id (user_id is 0).
        if ($row['user_id'] > 0) {
            $this->db()->delete()
                ->from('visits_online')
                ->where(['session_id' => $row['session_id'], 'user_id' => 0])
                ->execute();
        }

        $this->cleanUpOnline();

        // The dedup must use the same time base the row is written with:
        // the site-local "today" via \Ilch\Date (as used by the insert below),
        // not the DB server's CURDATE().
        $today = $date->format('Y-m-d', true);

        // These values are interpolated into raw SQL below, so they must be
        // escaped — session_id and especially the IP address can originate
        // from client-supplied data (e.g. X-Forwarded-For).
        $escapedSessionId = $this->db()->escape($row['session_id']);
        $escapedIp = $this->db()->escape($row['ip']);
        $userId = (int) $row['user_id'];

        $sql = 'SELECT id
            FROM `[prefix]_visits_stats`';

        // Order by id and limit of 1 is necessary as because of a previous bug the database might contain multiple rows with the same user_id or ip-address on the same day.
        if ($userId) {
            // Try to identify by session_id first as it should not change while the user is using the site.
            // If the user returns later (possible new session and re-authenticated with the remember me cookie) try to use the user id.
            $sql .= ' WHERE (`session_id` = "' . $escapedSessionId . '" OR `user_id` = "' . $userId . '") AND YEAR(`date`) = YEAR("' . $today . '") AND MONTH(`date`) = MONTH("' . $today . '") AND DAY(`date`) = DAY("' . $today . '")
                  ORDER BY `id` DESC LIMIT 1';
        } else {
            // Session id might still be the same. If this returns no result then (in case of guests) fall-back to ip-address to avoid counting every visit with dropped session id.
            $sql .= ' WHERE (`session_id` = "' . $escapedSessionId . '" OR (`ip_address` = "' . $escapedIp . '" AND `user_id` = 0)) AND YEAR(`date`) = YEAR("' . $today . '") AND MONTH(`date`) = MONTH("' . $today . '") AND DAY(`date`) = DAY("' . $today . '")
                  ORDER BY `id` DESC LIMIT 1';
        }

        $uniqueUser = $this->db()->queryCell($sql);

        if ($uniqueUser) {
            $this->db()->update('visits_stats')
                ->values(['user_id' => $row['user_id'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'lang' => $row['lang']])
                ->where(['id' => $uniqueUser])
                ->execute();
        } else {
            $this->db()->insert('visits_stats')
                ->values(['user_id' => $row['user_id'], 'session_id' => $row['session_id'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'ip_address' => $row['ip'], 'referer' => $row['referer'], 'lang' => $row['lang'], 'date' => $date->format('Y-m-d H:i:s', true)])
                ->execute();
        }
    }

    /**
     * Deletes a user from list of online users.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUserOnline(int $userId): bool
    {
        return $this->db()->delete('visits_online')
            ->where(['user_id' => $userId])
            ->execute();
    }

    /**
     * Clean up the visits_online table.
     * By default, rows for users will not be deleted.
     *
     * @param bool $keepUsers Set to false to delete rows for users, too.
     * @since 2.1.20
     */
    public function cleanUpOnline(bool $keepUsers = true)
    {
        $date = new \Ilch\Date();
        $date->modify('-1 day');

        $where = [
            'date_last_activity <' => $date->format('Y-m-d H:i:s', true),
        ];

        if ($keepUsers) {
            $where['user_id'] = 0;
        }

        $this->db()->delete()
            ->from('visits_online')
            ->where($where)
            ->execute();
    }

    /**
     * Check if a specified browser was seen before.
     *
     * @param string|null $browser
     * @return bool
     * @since 2.1.25
     */
    public function browserSeenBefore(?string $browser): bool
    {
        return $this->columnWithValueExists('browser', $browser);
    }

    /**
     * Check if a specified OS was seen before.
     *
     * @param string|null $os
     * @return bool
     * @since 2.1.25
     */
    public function osSeenBefore(?string $os): bool
    {
        return $this->columnWithValueExists('os', $os);
    }

    /**
     * Check if there is a row with a specified column containing a specific
     * value.
     *
     * History-aware: once the retention purge has deleted old raw rows, a
     * value may only remain in the aggregate tables. The daily aggregate
     * covers every historical day that ever had events (purged days are never
     * rebuilt, so their rows persist), while the raw table covers the recent
     * window — together they are the complete "ever seen" set.
     *
     * @param string $column
     * @param string|null $value
     * @return bool
     * @since 2.1.25
     */
    private function columnWithValueExists(string $column, ?string $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        $value = $this->db()->escape($value);

        return (bool)$this->db()->queryCell(
                "SELECT 1 FROM `[prefix]_visits_stats` WHERE `$column` = \"$value\" LIMIT 1"
            )
            || (bool)$this->db()->queryCell(
                "SELECT 1 FROM `[prefix]_visits_stats_daily` WHERE `$column` = \"$value\" LIMIT 1"
            );
    }

    /**
     * @return StatisticAggregate
     */
    private function aggregateMapper(): StatisticAggregate
    {
        return new StatisticAggregate();
    }

    /**
     * @param string $level One of 'daily', 'monthly', 'yearly'
     * @return string 'Y-m-d' frontier (epoch when not set yet)
     */
    private function frontier(string $level): string
    {
        if ($this->frontierCache === null) {
            $ag = $this->aggregateMapper();
            $this->frontierCache = [
                'daily' => $ag->getFrontier('daily'),
                'monthly' => $ag->getFrontier('monthly'),
                'yearly' => $ag->getFrontier('yearly'),
            ];
        }

        return $this->frontierCache[$level];
    }

    /**
     * Site-local "today" — the same calendar the raw rows are written with.
     *
     * @return string 'Y-m-d'
     */
    private function siteToday(): string
    {
        return $this->aggregateMapper()->siteToday();
    }

    /**
     * @param int $year
     * @param int $month
     * @return string 'Y-m-01'
     * @throws \InvalidArgumentException
     */
    private function monthStart(int $year, int $month): string
    {
        $start = sprintf('%d-%02d-01', $year, $month);
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-01$/', $start)) {
            throw new \InvalidArgumentException('Invalid year/month pair: ' . $year . '-' . $month);
        }

        return $start;
    }

    /**
     * Validate a machine-generated boundary literal before it is
     * interpolated.
     *
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    private function assertDateLiteral(string $value): string
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            throw new \InvalidArgumentException('Invalid Y-m-d date literal: ' . $value);
        }

        return $value;
    }

    /**
     * @param string $date 'Y-m-d'
     * @return string 'YYYY-01-01'
     */
    private function yearStartOf(string $date): string
    {
        return substr($this->assertDateLiteral($date), 0, 4) . '-01-01';
    }

    /**
     * Resolve a month query range: the given month when a valid (year, month)
     * pair was given, the current month otherwise.
     *
     * @param int|null $year
     * @param int|null $month
     * @return array{0: string, 1: string} [start, endExclusive), 'Y-m-d'
     */
    private function resolveMonthRange(?int $year, ?int $month): array
    {
        $ag = $this->aggregateMapper();

        if ($year !== null && $month !== null && (int)$year > 0 && (int)$month > 0) {
            $start = $this->monthStart((int)$year, (int)$month);
        } else {
            $start = $ag->firstOfMonth($ag->siteToday());
        }

        return [$start, $ag->addMonths($start, 1)];
    }

    /**
     * Resolve a year query range: the given year, or the current year.
     *
     * @param int|null $year
     * @return array{0: string, 1: string} [start, endExclusive), 'Y-m-d'
     */
    private function resolveYearRange(?int $year): array
    {
        $year = ($year !== null && (int)$year > 0) ? (int)$year : (int)substr($this->siteToday(), 0, 4);

        return [$year . '-01-01', ($year + 1) . '-01-01'];
    }

    /**
     * Resolve a month/year/all-time query range (a month only counts together
     * with a year, mirroring the legacy filter semantics).
     *
     * @param int|null $year
     * @param int|null $month
     * @return array{0: string, 1: string} [start, endExclusive), 'Y-m-d'
     */
    private function resolvePeriodRange(?int $year, ?int $month): array
    {
        $ag = $this->aggregateMapper();

        if ($year !== null && $month !== null && (int)$year > 0 && (int)$month > 0) {
            $start = $this->monthStart((int)$year, (int)$month);
            return [$start, $ag->addMonths($start, 1)];
        }

        if ($year !== null && (int)$year > 0) {
            $year = (int)$year;
            return [$year . '-01-01', ($year + 1) . '-01-01'];
        }

        // All time: the yearly tier covers everything before the yearly
        // frontier, the raw tail covers up to the end of today.
        return [self::ALL_TIME_START, $ag->addDays($ag->siteToday(), 1)];
    }

    /**
     * Range predicate for raw date queries (served by idx_date); '' when the
     * query is unfiltered (all time).
     *
     * @param int|null $year
     * @param int|null $month
     * @return string
     */
    private function rawPeriodWhere(?int $year, ?int $month): string
    {
        $ag = $this->aggregateMapper();

        if ($year !== null && $month !== null && (int)$year > 0 && (int)$month > 0) {
            $start = $this->monthStart((int)$year, (int)$month);
            $end = $ag->addMonths($start, 1);

            return ' WHERE `date` >= "' . $start . ' 00:00:00" AND `date` < "' . $end . ' 00:00:00"';
        }

        if ($year !== null && (int)$year > 0) {
            $year = (int)$year;

            return ' WHERE `date` >= "' . $year . '-01-01 00:00:00" AND `date` < "' . ($year + 1) . '-01-01 00:00:00"';
        }

        return '';
    }

    /**
     * Exact visit total over the calendar-aligned range [$start, $end).
     *
     * Combines the aggregate tiers (yearly, monthly, daily) for the fully
     * processed prefix with an indexed raw count for the unprocessed tail,
     * split at the persisted aggregation frontiers. The tiers are disjoint,
     * so the total is exact in every state of the catch-up backlog.
     *
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @return int
     */
    private function sumRange(string $start, string $end): int
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        $yearlyFrontier = $this->frontier('yearly');
        $monthlyFrontier = $this->frontier('monthly');
        $dailyFrontier = $this->frontier('daily');

        $total = 0;
        $cursor = $start;

        // Whole-year tier: only when the range starts at a year boundary.
        // Every selected year is fully present in the yearly table.
        if ($cursor === $this->yearStartOf($cursor)) {
            $lastWholeYear = (int)substr($end, 0, 4) - 1;
            $lastCompleteYear = (int)substr($yearlyFrontier, 0, 4) - 1;
            $tierEndYear = min($lastWholeYear, $lastCompleteYear);

            if ((int)substr($cursor, 0, 4) <= $tierEndYear) {
                $total += $this->sumTierYearly((int)substr($cursor, 0, 4), $tierEndYear + 1);
                $cursor = ($tierEndYear + 1) . '-01-01';
            }
        }

        $tierEnd = min($end, $monthlyFrontier);
        if ($cursor < $tierEnd) {
            $total += $this->sumTierMonthly($cursor, $tierEnd);
            $cursor = $tierEnd;
        }

        $tierEnd = min($end, $dailyFrontier);
        if ($cursor < $tierEnd) {
            $total += $this->sumTierDaily($cursor, $tierEnd);
            $cursor = $tierEnd;
        }

        $rawStart = max($cursor, $dailyFrontier);
        if ($rawStart < $end) {
            $total += $this->sumTierRaw($rawStart, $end);
        }

        return $total;
    }

    /**
     * Sum of the yearly aggregate rows for whole years in [$yearStart, $yearEndExclusive).
     *
     * @param int $yearStart
     * @param int $yearEndExclusive
     * @return int
     */
    private function sumTierYearly(int $yearStart, int $yearEndExclusive): int
    {
        return (int)$this->db()->queryCell(
            "SELECT COALESCE(SUM(`visits`), 0) FROM `[prefix]_visits_stats_yearly`
             WHERE `year` >= $yearStart AND `year` < $yearEndExclusive"
        );
    }

    /**
     * Sum of the monthly aggregate rows for months in [$start, $endExclusive).
     *
     * @param string $start 'Y-m-01'
     * @param string $end 'Y-m-01' exclusive
     * @return int
     */
    private function sumTierMonthly(string $start, string $end): int
    {
        return (int)$this->db()->queryCell(
            "SELECT COALESCE(SUM(`visits`), 0) FROM `[prefix]_visits_stats_monthly`
             WHERE `month` >= \"$start\" AND `month` < \"$end\""
        );
    }

    /**
     * Sum of the daily aggregate rows for days in [$start, $endExclusive).
     *
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @return int
     */
    private function sumTierDaily(string $start, string $end): int
    {
        return (int)$this->db()->queryCell(
            "SELECT COALESCE(SUM(`visits`), 0) FROM `[prefix]_visits_stats_daily`
             WHERE `date` >= \"$start\" AND `date` < \"$end\""
        );
    }

    /**
     * Indexed raw count for days in [$start, $endExclusive) (idx_date).
     *
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @return int
     */
    private function sumTierRaw(string $start, string $end): int
    {
        return (int)$this->db()->queryCell(
            "SELECT COUNT(*) FROM `[prefix]_visits_stats`
             WHERE `date` >= \"$start 00:00:00\" AND `date` < \"$end 00:00:00\""
        );
    }

    /**
     * Per-dimension visit totals over a calendar-aligned range, combining
     * the aggregate tiers with the raw tail.
     *
     * Yearly rows cover whole years that are fully rolled up, monthly rows
     * cover months before the monthly frontier, daily rows cover processed
     * days, and the raw tail covers the still-open recent window (bounded
     * by idx_date). The tiers are disjoint, so their sums merge exactly.
     *
     * @param string[] $groupColumns Backticked dimension columns
     * @param string $keyExpr SQL expression producing the merge key
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @param string $filter Optional extra predicate (escaped values only)
     * @return array<string, int> key => visits
     */
    private function visitsByDimension(array $groupColumns, string $keyExpr, string $start, string $end, string $filter = ''): array
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        $yearlyFrontier = $this->frontier('yearly');
        $monthlyFrontier = $this->frontier('monthly');
        $dailyFrontier = $this->frontier('daily');

        $rows = [];
        $cursor = $start;

        // Whole-year tier: only when the range starts at a year boundary.
        // Every selected year is fully present in the yearly table.
        if ($cursor === $this->yearStartOf($cursor)) {
            $lastWholeYear = (int)substr($end, 0, 4) - 1;
            $lastCompleteYear = (int)substr($yearlyFrontier, 0, 4) - 1;
            $tierEndYear = min($lastWholeYear, $lastCompleteYear);

            if ((int)substr($cursor, 0, 4) <= $tierEndYear) {
                $rows = $this->mergeDimRows(
                    $rows,
                    $this->aggregateDimRows('visits_stats_yearly', 'year', $groupColumns, $keyExpr, $cursor, ($tierEndYear + 1) . '-01-01', $filter)
                );
                $cursor = ($tierEndYear + 1) . '-01-01';
            }
        }

        // Monthly tier: months fully rolled up (before the monthly frontier).
        $tierEnd = min($end, $monthlyFrontier);
        if ($cursor < $tierEnd) {
            $rows = $this->mergeDimRows($rows, $this->aggregateDimRows('visits_stats_monthly', 'month', $groupColumns, $keyExpr, $cursor, $tierEnd, $filter));
            $cursor = $tierEnd;
        }

        // Daily tier: processed days (before the daily frontier).
        $tierEnd = min($end, $dailyFrontier);
        if ($cursor < $tierEnd) {
            $rows = $this->mergeDimRows($rows, $this->aggregateDimRows('visits_stats_daily', 'date', $groupColumns, $keyExpr, $cursor, $tierEnd, $filter));
            $cursor = $tierEnd;
        }

        // Raw tail: the recent window, still being written.
        $rawStart = max($cursor, $dailyFrontier);
        if ($rawStart < $end) {
            $rows = $this->mergeDimRows($rows, $this->rawDimRows($groupColumns, $keyExpr, $rawStart, $end, $filter));
        }

        return $rows;
    }

    /**
     * Per-dimension visit totals from one aggregate tier table.
     *
     * @param string $table 'visits_stats_daily' | 'visits_stats_monthly' | 'visits_stats_yearly'
     * @param string $periodColumn 'date' | 'month' | 'year'
     * @param string[] $groupColumns Backticked dimension columns (or key expressions)
     * @param string $keyExpr SQL expression producing the merge key
     * @param string $start 'Y-m-d' (only the year part is used for the yearly table)
     * @param string $end 'Y-m-d' exclusive
     * @param string $filter Optional extra predicate (escaped values only)
     * @return array<string, int> key => visits
     */
    private function aggregateDimRows(string $table, string $periodColumn, array $groupColumns, string $keyExpr, string $start, string $end, string $filter = ''): array
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        if ($periodColumn === 'year') {
            $periodSql = sprintf('`year` >= %d AND `year` < %d', (int)substr($start, 0, 4), (int)substr($end, 0, 4));
        } else {
            $periodSql = sprintf('`%s` >= "%s" AND `%s` < "%s"', $periodColumn, $start, $periodColumn, $end);
        }

        $sql = "SELECT $keyExpr AS `dim`, SUM(`visits`) AS `visits`
                FROM `[prefix]_$table`
                WHERE $periodSql" . ($filter !== '' ? " AND $filter" : '') . "
                GROUP BY " . implode(', ', $groupColumns);

        $rows = [];
        foreach ($this->db()->queryArray($sql) as $row) {
            $rows[(string)$row['dim']] = (int)$row['visits'];
        }

        return $rows;
    }

    /**
     * Per-dimension visit counts from the raw table over one day range
     * (idx_date), using the same grouping as the aggregate tiers.
     *
     * @param string[] $groupColumns Backticked dimension columns (or key expressions)
     * @param string $keyExpr SQL expression producing the merge key
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @param string $filter Optional extra predicate (escaped values only)
     * @return array<string, int> key => visits
     */
    private function rawDimRows(array $groupColumns, string $keyExpr, string $start, string $end, string $filter = ''): array
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        $sql = "SELECT $keyExpr AS `dim`, COUNT(*) AS `visits`
                FROM `[prefix]_visits_stats`
                WHERE `date` >= \"$start 00:00:00\" AND `date` < \"$end 00:00:00\"" . ($filter !== '' ? " AND $filter" : '') . "
                GROUP BY " . implode(', ', $groupColumns);

        $rows = [];
        foreach ($this->db()->queryArray($sql) as $row) {
            $rows[(string)$row['dim']] = (int)$row['visits'];
        }

        return $rows;
    }

    /**
     * Per-weekday visit totals from the daily aggregate table for days in
     * [$start, $endExclusive): visits summed per weekday, display date = the
     * latest day of that weekday in range.
     *
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @return array<int, array{visits: int, date_full: string}> weekday (0..6) => row
     */
    private function aggregateWeekdayRows(string $start, string $end): array
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        $sql = "SELECT WEEKDAY(`date`) AS `date_week`, MAX(`date`) AS `date_full`, SUM(`visits`) AS `visits`
                FROM `[prefix]_visits_stats_daily`
                WHERE `date` >= \"$start\" AND `date` < \"$end\"
                GROUP BY WEEKDAY(`date`)";

        $rows = [];
        foreach ($this->db()->queryArray($sql) as $row) {
            $rows[(int)$row['date_week']] = ['visits' => (int)$row['visits'], 'date_full' => (string)$row['date_full']];
        }

        return $rows;
    }

    /**
     * Per-weekday visit counts from the raw table for days in
     * [$start, $endExclusive) (idx_date), same shape as
     * aggregateWeekdayRows().
     *
     * @param string $start 'Y-m-d'
     * @param string $end 'Y-m-d' exclusive
     * @return array<int, array{visits: int, date_full: string}> weekday (0..6) => row
     */
    private function rawWeekdayRows(string $start, string $end): array
    {
        $start = $this->assertDateLiteral($start);
        $end = $this->assertDateLiteral($end);

        $sql = "SELECT WEEKDAY(`date`) AS `date_week`, MAX(DATE(`date`)) AS `date_full`, COUNT(*) AS `visits`
                FROM `[prefix]_visits_stats`
                WHERE `date` >= \"$start 00:00:00\" AND `date` < \"$end 00:00:00\"
                GROUP BY WEEKDAY(`date`)";

        $rows = [];
        foreach ($this->db()->queryArray($sql) as $row) {
            $rows[(int)$row['date_week']] = ['visits' => (int)$row['visits'], 'date_full' => (string)$row['date_full']];
        }

        return $rows;
    }

    /**
     * Merge per-weekday rows from several (disjoint) range result sets:
     * visits summed, display date = the latest date seen.
     *
     * @param array<int, array{visits: int, date_full: string}> ...$rowSets weekday (0..6) => row
     * @return array<int, array{visits: int, date_full: string}> weekday (0..6) => row
     */
    private function mergeWeekdayRows(array ...$rowSets): array
    {
        $merged = [];
        foreach ($rowSets as $rowSet) {
            foreach ($rowSet as $weekday => $data) {
                if (!isset($merged[$weekday])) {
                    $merged[$weekday] = $data;
                } else {
                    $merged[$weekday]['visits'] += $data['visits'];
                    if ($data['date_full'] > $merged[$weekday]['date_full']) {
                        $merged[$weekday]['date_full'] = $data['date_full'];
                    }
                }
            }
        }

        return $merged;
    }

    /**
     * Per-year totals from the yearly aggregate table for the years in
     * [$start, $endExclusive).
     *
     * @param string $start 'Y-m-d' (year part used)
     * @param string $end 'Y-m-d' exclusive (year part used)
     * @return array<string, int> 'year' => visits
     */
    private function aggregateYearRows(string $start, string $end): array
    {
        $sql = "SELECT `year`, SUM(`visits`) AS `visits`
                FROM `[prefix]_visits_stats_yearly`
                WHERE `year` >= " . (int)substr($this->assertDateLiteral($start), 0, 4) . " AND `year` < " . (int)substr($this->assertDateLiteral($end), 0, 4) . "
                GROUP BY `year`";

        $rows = [];
        foreach ($this->db()->queryArray($sql) as $row) {
            $rows[(string)$row['year']] = (int)$row['visits'];
        }

        return $rows;
    }

    /**
     * Year of the earliest recorded visit, or null when no data exists at
     * all. Keeps the open-year loop in getVisitsYear() bounded on sites with
     * a long history (avoids iterating from the epoch frontier).
     *
     * @return int|null
     */
    private function firstDataYear(): ?int
    {
        $candidates = [];

        $minYear = $this->db()->queryCell("SELECT MIN(`year`) FROM `[prefix]_visits_stats_yearly`");
        if ($minYear !== null && $minYear !== '') {
            $candidates[] = (int)$minYear;
        }

        $minMonth = $this->db()->queryCell("SELECT MIN(`month`) FROM `[prefix]_visits_stats_monthly`");
        if ($minMonth !== null && $minMonth !== '') {
            $candidates[] = (int)substr((string)$minMonth, 0, 4);
        }

        $minDate = $this->db()->queryCell("SELECT MIN(`date`) FROM `[prefix]_visits_stats_daily`");
        if ($minDate !== null && $minDate !== '') {
            $candidates[] = (int)substr((string)$minDate, 0, 4);
        }

        $minRaw = $this->db()->queryCell("SELECT MIN(`date`) FROM `[prefix]_visits_stats`");
        if ($minRaw !== null && $minRaw !== '') {
            $candidates[] = (int)substr((string)$minRaw, 0, 4);
        }

        return $candidates === [] ? null : min($candidates);
    }

    /**
     * Merge per-dimension rows from several (disjoint) tier result sets.
     *
     * @param array<string, int> ...$rowSets key => visits
     * @return array<string, int> key => visits
     */
    private function mergeDimRows(array ...$rowSets): array
    {
        $merged = [];
        foreach ($rowSets as $rowSet) {
            foreach ($rowSet as $key => $visits) {
                $merged[$key] = ($merged[$key] ?? 0) + (int)$visits;
            }
        }

        return $merged;
    }
}
