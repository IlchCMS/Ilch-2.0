<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Mappers;

use Modules\Statistic\Models\Statistic as StatisticModel;

defined('ACCESS') or die('no direct access');

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
            $statisticModel->setBrowser($entries['browser']);
            $statisticModel->setDateLastActivity($entries['date_last_activity']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsYearMonthDay()
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`
                GROUP BY date
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

    public function getVisitsYearMonth()
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`
                GROUP BY YEAR(date), MONTH(date)
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

    public function getVisitsYear()
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`
                GROUP BY YEAR(date)
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

    public function getVisitsBrowser()
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`
                GROUP BY browser
                ORDER BY visits DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setBrowser($entries['browser']);
            $entry[] = $statisticModel;
        }

        return $entry;
    }

    public function getVisitsOS()
    {
        $sql = 'SELECT *, COUNT(id) AS visits
                FROM `[prefix]_visits_stats`
                GROUP BY os
                ORDER BY visits DESC';

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $statisticModel = new StatisticModel();
            $statisticModel->setVisits($entries['visits']);
            $statisticModel->setOS($entries['os']);
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
        $date->modify('-3 minutes');

        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_online`
                WHERE `date_last_activity` > "'.$date->toDb().'"';
        
        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getRegistUserCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_users`
                WHERE `confirmed` = 1';
        
        $entries = $this->db()->queryCell($sql);

        return $entries;
    }

    /**
     * @return integer
     */
    public function getVisitsCount($date = null)
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`';
        
        if ($date != null) {
            $sql .= 'WHERE `date` = "'.$date.'"';
        }

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getVisitsMonthCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`
                WHERE YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())';

        $visits = $this->db()->queryCell($sql);

        return $visits;
    }

    public function getVisitsYearCount()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_visits_stats`
                WHERE YEAR(date) = YEAR(CURDATE())';

        $visits = $this->db()->queryCell($sql);

        return $visits;
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
                ->values(array('site' => $row['site'], 'os' => $row['os'], 'browser' => $row['browser'], 'date_last_activity' => $date->format("Y-m-d H:i:s", true)))
                ->where(array('id' => $visitId))
                ->execute();
        } else {
            $this->db()->insert('visits_online')
                ->values(array('user_id' => $row['user_id'], 'site' => $row['site'], 'os' => $row['os'], 'browser' => $row['browser'], 'ip_address' => $row['ip'], 'date_last_activity' => $date->format("Y-m-d H:i:s", true)))
                ->execute();
        }

        $uniqueUser = (int)$this->db()->select('id')
            ->from('visits_stats')
            ->where(array('ip_address' => $row['ip'], 'date' => $date->format('Y-m-d')))
            ->execute()
            ->fetchCell();

        if ($uniqueUser) {
            $this->db()->update('visits_stats')
                ->values(array('os' => $row['os'], 'browser' => $row['browser']))
                ->where(array('id' => $uniqueUser))
                ->execute();
        } else {
            $this->db()->insert('visits_stats')
                ->values(array('referer' => $row['referer'], 'os' => $row['os'], 'browser' => $row['browser'], 'ip_address' => $row['ip'], 'date' => $date->format('Y-m-d')))
                ->execute();
        }
    }
}
