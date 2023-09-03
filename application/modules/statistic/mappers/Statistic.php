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
            if ($row['user_id']) {
                $users[] = $userMapper->getUserById($row['user_id']);
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
     * @return User[]
     * @since 2.1.20
     */
    public function getWhoWasOnline(): array
    {
        $userMapper = new UserMapper();
        $date = new \Ilch\Date();
        $dateString = $date->format('Y-m-d H:i:s', true);

        $sql = 'SELECT `[prefix]_visits_stats`.user_id, `[prefix]_visits_stats`.date, `[prefix]_users`.*
                FROM `[prefix]_visits_stats`
                INNER JOIN `[prefix]_users` ON user_id = `[prefix]_users`.id
                WHERE YEAR(`date`) = YEAR("' . $dateString . '") AND MONTH(`date`) = MONTH("' . $dateString
                . '") AND DAY(`date`) = DAY("' . $dateString . '") AND `user_id` > 0
                GROUP BY `user_id`';

        $rows = $this->db()->queryArray($sql);

        $users = [];
        foreach ($rows as $row) {
            $users[] = $userMapper->loadFromArray($row);
        }

        return $users;
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsHour(?int $year = null, ?int $month = null): ?array
    {
        $sql = 'SELECT
                HOUR(`date`) AS `date_hour`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }
        $sql .= ' GROUP BY HOUR(`date`)
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
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsDay(?int $year = null, ?int $month = null): ?array
    {
        $sql = 'SELECT
                MAX(DATE(`date`)) AS `date_full`,
                WEEKDAY(`date`) AS `date_week`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }
        $sql .= ' GROUP BY WEEKDAY(`date`)
                ORDER BY `date_week` ASC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date_full']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsYearMonthDay(?int $year = null, ?int $month = null): ?array
    {
        $sql = 'SELECT 
                DATE(`date`) AS `date_full`,
                YEAR(`date`) AS `date_year`,
                MONTH(`date`) AS `date_month`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())';
        }
        $sql .= ' GROUP BY YEAR(`date`), MONTH(`date`), DATE(`date`)
                ORDER BY `date_full` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date_full']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @return null|StatisticModel[]
     */
    public function getVisitsYearMonth(?int $year = null): ?array
    {
        $sql = 'SELECT YEAR(`date`) AS `date_year`, MONTH(`date`) AS `date_month`, COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE())';
        }
        $sql .= ' GROUP BY YEAR(`date`), MONTH(`date`)
                ORDER BY `date_month` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date_year'] . '-' . $entries['date_month'] . '-01');
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @return null|StatisticModel[]
     */
    public function getVisitsYear(?int $year = null): ?array
    {
        $sql = 'SELECT YEAR(`date`) AS `year_full`, COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }
        $sql .= ' GROUP BY YEAR(`date`)
                  ORDER BY `year_full` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['year_full'] . '-01-01');
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @param string|null $browser
     * @return null|StatisticModel[]
     */
    public function getVisitsBrowser(?int $year = null, ?int $month = null, ?string $browser = null): ?array
    {
        $browser = $this->db()->escape($browser);
        $sql = 'SELECT `browser`, COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null && $browser != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '") AND browser = "' . $browser . '"';
        } elseif ($month == null && $year != null && $browser != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND browser = "' . $browser . '"';
        } elseif ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($month == null && $year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }

        $sql .= ' GROUP BY `browser`
                  ORDER BY `visits` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setBrowser($entries['browser']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @return null|StatisticModel[]
     */
    public function getVisitsLanguage(?int $year = null, ?int $month = null): ?array
    {
        $sql = 'SELECT
                MAX(`date`),
                `lang`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($month == null && $year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }

        $sql .= ' GROUP BY `lang`
                ORDER BY `visits` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setLang($entries['lang']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @param string|null $os
     * @return null|StatisticModel[]
     */
    public function getVisitsOS(?int $year = null, int $month = null, ?string $os = null): ?array
    {
        $os = $this->db()->escape($os);
        $sql = 'SELECT 
                MAX(`date`),
                `os_version`,
                `os`,
                COUNT(`id`) AS `visits`
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null && $os != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '") AND os = "' . $os . '"';
        } elseif ($month == null && $year != null && $os != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND os = "' . $os . '"';
        } elseif ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($month == null && $year != null) {
            $date = $year . '-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        }

        $sql .= ' GROUP BY `os`,`os_version`
                  ORDER BY `visits` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setOS($entries['os']);
            $statisticModel->setOSVersion($entries['os_version']);
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
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $locale
     * @return int
     */
    public function getArticlesCount($groupIds = '3', string $locale = ''): int
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
     * @param string|null $date
     * @param int|null $year
     * @param int|null $month
     * @return int
     */
    public function getVisitsCount(?string $date = null, ?int $year = null, ?int $month = null): int
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01 00:00:00';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } elseif ($month == null && $year != null) {
            $date = $year . '-01-01 00:00:00';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '")';
        } elseif ($date != null) {
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '") AND DAY(`date`) = DAY("' . $date . '")';
        }

        return $this->db()->queryCell($sql);
    }

    /**
     * @param int|null $year
     * @param int|null $month
     * @return int
     */
    public function getVisitsMonthCount(?int $year = null, ?int $month = null): int
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`';
        if ($month != null && $year != null) {
            $date = $year . '-' . $month . '-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("' . $date . '") AND MONTH(`date`) = MONTH("' . $date . '")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())';
        }

        return $this->db()->queryCell($sql);
    }

    /**
     * @return int
     */
    public function getVisitsYearCount(): int
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`
                WHERE YEAR(`date`) = YEAR(CURDATE())';

        return $this->db()->queryCell($sql);
    }

    /**
     * @param int $count
     * @param int $totalcount
     * @return float
     */
    public function getPercent(int $count, int $totalcount): float
    {
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
                    '=Windows Phone 8=' => 'Phone 8.0',
                    '=Windows Phone 8.1=' => 'Phone 8.1',
                    '=Windows Phone 10=' => '10 Mobile',

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
                if (preg_match("=Firefox/([\.a-zA-Z0-9]*)=", $useragent)) {
                    return 'Firefox';
                }
                if (preg_match("=MSIE ([0-9]{1,2})\.[0-9]{1,2}=", $useragent)) {
                    return 'Internet Explorer';
                }
                if (preg_match("=rv:([0-9]{1,2})\.[0-9]{1,2}=", $useragent)) {
                    return 'Internet Explorer';
                }
                if (preg_match("=Opera[/ ]([0-9\.]+)=", $useragent)) {
                    return 'Opera';
                }
                if (preg_match("=OPR\/([0-9\.]*)=", $useragent)) {
                    return 'Opera';
                }
                if (preg_match("=Edge/([0-9\.]*)=", $useragent)) {
                    return 'Edge';
                }
                if (preg_match("=Edg/([0-9\.]*)=", $useragent)) {
                    return 'Edge';
                }
                if (preg_match("=Vivaldi\/([0-9\.]*)=", $useragent)) {
                    return 'Vivaldi';
                }
                if (preg_match("=SamsungBrowser\/([0-9\.]*)=", $useragent)) {
                    return 'Samsung Browser';
                }
                if (preg_match("=Chrome/([0-9\.]*)=", $useragent)) {
                    return 'Chrome';
                }
                if (preg_match('=Safari/=', $useragent)) {
                    return 'Safari';
                }
                if (strpos($useragent, 'Konqueror') !== false) {
                    return 'Konqueror';
                }
                if (preg_match('=Netscape|Navigator=', $useragent)) {
                    return 'Netscape';
                }

                return '';
            }

            if (preg_match("=Firefox/([\.a-zA-Z0-9]*)=", $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match("=MSIE ([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match("=rv:([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match("=Opera[/ ]([0-9\.]+)=", $useragent, $browser)) {
                return $browser[1];
            }
            if (preg_match("=OPR\/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match("=Edge/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match("=Edg/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match("=Vivaldi\/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match("=Chrome/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            }
            if (preg_match('=Safari/=', $useragent)) {
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
        // but didn't logged out or not using remember me, returned as guest with different session_id (user_id is 0).
        if ($row['user_id'] > 0) {
            $this->db()->delete()
                ->from('visits_online')
                ->where(['session_id' => $row['session_id'], 'user_id' => 0])
                ->execute();
        }

        $this->cleanUpOnline();

        $sql = 'SELECT id
                FROM `[prefix]_visits_stats`';

        // Order by id and limit of 1 is necessary as because of a previous bug the database might contain multiple rows with the same user_id or ip-address on the same day.
        if ($row['user_id']) {
            // Try to identify by session_id first as it should not change while the user is using the site.
            // If the user returns later (possible new session and re-authenticated with the remember me cookie) try to use the user id.
            $sql .= ' WHERE (`session_id` = "' . $row['session_id'] . '" OR `user_id` = "' . $row['user_id'] . '") AND YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) AND DAY(`date`) = DAY(CURDATE())
                      ORDER BY `id` DESC LIMIT 1';
        } else {
            // Session id might still be the same. If this returns no result then (in case of guests) fall-back to ip-address to avoid counting every visit with dropped session id.
            $sql .= ' WHERE (`session_id` = "' . $row['session_id'] . '" OR (`ip_address` = "' . $row['ip'] . '" AND `user_id` = 0)) AND YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) AND DAY(`date`) = DAY(CURDATE())
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
     * Check if there is a row with a specified column contains a specific value.
     *
     * @param string $column
     * @param string|null $value
     * @return bool
     * @since 2.1.25
     */
    private function columnWithValueExists(string $column, ?string $value): bool
    {
        return (bool)$this->db()->select('id')
            ->from('visits_stats')
            ->where([$column => $value])
            ->execute()
            ->fetchCell();
    }
}
