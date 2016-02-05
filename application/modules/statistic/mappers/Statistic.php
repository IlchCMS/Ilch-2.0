<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Mappers;

use Modules\Statistic\Models\Statistic as StatisticModel;

class Statistic extends \Ilch\Mapper
{
    public function getVisitsOnlineUser()
    {
        $userMapper = new \Modules\User\Mappers\User();
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $sql = 'SELECT *
                FROM `[prefix]_visits_online`
                WHERE `date_last_activity` > "'.$date->format("Y-m-d H:i:s", true).'"
                AND `user_id` > 0';

        $rows = $this->db()->queryArray($sql);

        $users = array();

        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['user_id']);
        }

        return $users;
    }

    public function getVisitsOnline()
    {
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $sql = 'SELECT *
                FROM `[prefix]_visits_online`
                WHERE `date_last_activity` > "'.$date->format("Y-m-d H:i:s", true).'"
                ORDER BY date_last_activity DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setUserId($entries['user_id']);
            $statisticModel->setSite($entries['site']);
            $statisticModel->setIPAdress($entries['ip_address']);
            $statisticModel->setOS($entries['os']);
            $statisticModel->setOSVersion($entries['os_version']);
            $statisticModel->setBrowser($entries['browser']);
            $statisticModel->setBrowserVersion($entries['browser_version']);
            $statisticModel->setDateLastActivity($entries['date_last_activity']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsHour($year = null, $month = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } elseif ($year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        }
        $sql .= ' GROUP BY HOUR(`date`)
                ORDER BY HOUR(`date`) DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsDay($year = null, $month = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } elseif ($year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        }
        $sql .= ' GROUP BY WEEKDAY(`date`)
                ORDER BY `date` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsYearMonthDay($year = null, $month = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())';
        }
        $sql .= ' GROUP BY Year(`date`), Month(`date`), DAY(`date`)
                ORDER BY date DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsYearMonth($year = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE())';
        }
        $sql .= ' GROUP BY YEAR(`date`), MONTH(`date`)
                ORDER BY `date` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsYear($year = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        }
        $sql .= ' GROUP BY YEAR(`date`)
                ORDER BY `date` DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setDate($entries['date']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsBrowser($year = null, $month = null, $browser = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null AND $browser != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'") AND browser = "'.$browser.'"
                      GROUP BY browser_version
                      ORDER BY visits DESC';
        } elseif ($month == null AND $year != null AND $browser != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND browser = "'.$browser.'"
                      GROUP BY browser_version
                      ORDER BY visits DESC';
        } elseif ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")
                      GROUP BY browser
                      ORDER BY visits DESC';
        } elseif ($month == null AND $year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")
                      GROUP BY browser
                      ORDER BY visits DESC';
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setBrowser($entries['browser']);
            $statisticModel->setBrowserVersion($entries['browser_version']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsLanguage($year = null, $month = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } else if ($month == null AND $year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        }
        $sql .= ' GROUP BY lang
                ORDER BY visits DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setLang($entries['lang']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsOS($year = null, $month = null, $os = null)
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null AND $os != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'") AND os = "'.$os.'"
                      GROUP BY os_version
                      ORDER BY visits DESC';
        } elseif ($month == null AND $year != null AND $os != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND os = "'.$os.'"
                      GROUP BY os_version
                      ORDER BY visits DESC';
        } elseif ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")
                      GROUP BY os
                      ORDER BY visits DESC';
        } elseif ($month == null AND $year != null) {
            $date = $year.'-01-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")
                      GROUP BY os
                      ORDER BY visits DESC';
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

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
     * @return integer
     */
    public function getVisitsCountOnline()
    {
        $date = new \Ilch\Date();
        $date->modify('-5 minutes');

        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_online`
                WHERE `date_last_activity` > "'.$date->format("Y-m-d H:i:s", true).'"';

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getArticlesCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_articles`';

        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    public function getCommentsCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_comments`';

        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    public function getModulesCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_modules`
                WHERE `system` = 0';

        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    public function getRegistUserCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_users`
                WHERE `confirmed` = 1';

        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    public function getRegistNewUser()
    {
        $sql = 'SELECT MAX(id)
                FROM `[prefix]_users`
                WHERE `confirmed` = 1';

        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    /**
     * @return integer
     */
    public function getVisitsCount($date = null, $year = null, $month = null)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01 00:00:00';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } elseif ($month == null AND $year != null) {
            $date = $year.'-01-01 00:00:00';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'")';
        } elseif ($date != null) {
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'") AND DAY(`date`) = DAY("'.$date.'")';
        }

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getVisitsMonthCount($year = null, $month = null)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`';
        if ($month != null AND $year != null) {
            $date = $year.'-'.$month.'-01';
            $sql .= ' WHERE YEAR(`date`) = YEAR("'.$date.'") AND MONTH(`date`) = MONTH("'.$date.'")';
        } else {
            $sql .= ' WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())';
        }

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getVisitsYearCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`
                WHERE YEAR(`date`) = YEAR(CURDATE())';

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getPercent($count, $totalcount)
    {
        $percent = round(($count / $totalcount) * 100);

        return $percent;
    }

    public function getOS($name = null, $version = null) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if ($name != null) {
            $osArray = array(
                'Windows' => '=Windows NT|Windows Server 2003|Windows XP x64|Windows 98|Windows 95=',
                'Android' => '=Android=',
                'Linux' => '=Linux|Ubuntu|X11=',
                'SunOs' => '=SunOS=',
                'iPhone' => '=iPhone=',
                'iPad' => '=iPad=',
                'Mac OS' => '=Mac OS X=',
                'Macintosh' => '=Mac_PowerPC|Macintosh='
            );
        } elseif ($version != null) {
            $osArray = array(
                'XP' => '=Windows NT 5.1|Windows XP=',
                'Vista' => '=Windows NT 6.0|Windows Vista=',
                '7' => '=Windows NT 6.1|Windows 7=',
                '8' => '=Windows NT 6.2|Windows 8=',
                '8.1' => '=Windows NT 6.3|Windows 8.1=',
                '10' => '=Windows NT 10.0|Windows 10=',
                '2000' => '=Windows NT 5.0|Windows 2000=',
                'Server 2003' => '=Windows NT 5\.2|Windows Server 2003|Windows XP x64=',
                'NT' => '=Windows NT 4|WinNT4=',
                '98' => '=Windows 98=',
                '95' => '=Windows 95=',
            );
        }

        foreach ($osArray as $os => $regex) {
            if (preg_match($regex, $useragent)) {
                return $os;
            }
        }

        return 0;
    }

    public function getBrowser($version = null) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if ($version != null) {
            if (preg_match("=Firefox/([\.a-zA-Z0-9]*)=", $useragent)) {
                return ("Firefox");
            } elseif (preg_match("=MSIE ([0-9]{1,2})\.[0-9]{1,2}=", $useragent)) {
                return "Internet Explorer";
            } elseif (preg_match("=rv:([0-9]{1,2})\.[0-9]{1,2}=", $useragent)) {
                return "Internet Explorer";
            } elseif (preg_match("=Opera[/ ]([0-9\.]+)=", $useragent)) {
                return "Opera";
            } elseif (preg_match("=OPR\/([0-9\.]*)=", $useragent)) {
                return "Opera";
            } elseif (preg_match("=Edge/([0-9\.]*)=", $useragent)) {
                return "Edge";
            } elseif (preg_match("=Chrome/([0-9\.]*)=", $useragent)) {
                return "Chrome";
            } elseif (preg_match('=Safari/=', $useragent)) {
                return "Safari";
            } elseif (preg_match("=Konqueror=", $useragent)) {
                return "Konqueror";
            } elseif (preg_match("=Netscape|Navigator=", $useragent)) {
                return "Netscape";
            } else {
                return 0;
            }
        } else {
            if (preg_match("=Firefox/([\.a-zA-Z0-9]*)=", $useragent, $browser)) {
                return $browser[1];
            } elseif (preg_match("=MSIE ([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return $browser[1];
            } elseif (preg_match("=rv:([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return $browser[1];
            } elseif (preg_match("=Opera[/ ]([0-9\.]+)=", $useragent, $browser)) {
                return $browser[1];
            } elseif (preg_match("=OPR\/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            } elseif (preg_match("=Edge/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            } elseif (preg_match("=Chrome/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return $browser[1];
            } elseif (preg_match('=Safari/=', $useragent)) {
                if (preg_match('=Version/([\.0-9]*)=', $useragent, $browser)) {
                    $version = $browser[1];
                } else {
                    $version = 0;
                }
                return $version;
            } else {
                return 0;
            }
        }
    }

    /**
     * @param array $row
     */
    public function saveVisit($row)
    {
        $date = new \Ilch\Date();
        $visitId = (int) $this->db()->select('id')
            ->from('visits_online')
            ->where(array('user_id' => $row['user_id'], 'ip_address' => $row['ip']))
            ->execute()
            ->fetchCell();

        if ($visitId) {
            $this->db()->update('visits_online')
                ->values(array('site' => $row['site'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'lang' => $row['lang'], 'date_last_activity' => $date->format('Y-m-d H:i:s', true)))
                ->where(array('id' => $visitId))
                ->execute();

            if ($row['user_id']) {
                $this->db()->update('users')
                    ->values(array('date_last_activity' => $date->format('Y-m-d H:i:s', true)))
                    ->where(array('id' => $row['user_id']))
                    ->execute();
            }
        } else {
            $this->db()->insert('visits_online')
                ->values(array('user_id' => $row['user_id'], 'site' => $row['site'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'ip_address' => $row['ip'], 'lang' => $row['lang'], 'date_last_activity' => $date->format('Y-m-d H:i:s', true)))
                ->execute();
        }

        $sql = 'SELECT id
                FROM `[prefix]_visits_stats`
                WHERE ip_address = "'.$row['ip'].'" AND YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) AND DAY(`date`) = DAY(CURDATE())';

        $uniqueUser = $this->db()->queryCell($sql);

        if ($uniqueUser) {
            $this->db()->update('visits_stats')
                ->values(array('os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'lang' => $row['lang']))
                ->where(array('id' => $uniqueUser))
                ->execute();
        } else {
            $this->db()->insert('visits_stats')
                ->values(array('referer' => $row['referer'], 'os' => $row['os'], 'os_version' => $row['os_version'], 'browser' => $row['browser'], 'browser_version' => $row['browser_version'], 'ip_address' => $row['ip'], 'lang' => $row['lang'], 'date' => $date->format('Y-m-d H:i:s', true)))
                ->execute();
        }
    }
}
