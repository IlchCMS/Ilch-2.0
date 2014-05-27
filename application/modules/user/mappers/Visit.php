<?php
namespace Modules\User\Mappers;

defined('ACCESS') or die('no direct access');

/**
 * @package ilch
 */
class Visit extends \Ilch\Mapper
{
    /**
     * @return \Modules\User\Models\User[]
     */
    public function getVisitsOnlineUser()
    {
        $userMapper = new User();
        $date = new \Ilch\Date();
        $date->modify('-3 minutes');

        $sql = 'SELECT *
                FROM `[prefix]_visits_online`
                WHERE `date_last_activity` > "'.$date->toDb().'"
                AND `user_id` > 0';

        $rows = $this->db()->queryArray($sql);
        $users = array();

        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['user_id']);
        }

        return $users;
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

    /**
     * @param array $row
     */
    public function saveVisit($row)
    {
        $date = new \Ilch\Date();
        $visitId = (int) $this->db()->select('id')
            ->from('visits_online')
            ->where(array('ip_address' => $row['ip'], 'user_id' => $row['user_id']))
            ->execute()
            ->fetchCell();

        if ($visitId) {
            $this->db()->update('visits_online')
                ->values(array('date_last_activity' => $date->toDb()))
                ->where(array('id' => $visitId))
                ->execute();
        } else {
            $this->db()->insert('visits_online')
                ->values(array('date_last_activity' => $date->toDb(), 'ip_address' => $row['ip'], 'user_id' => $row['user_id']))
                ->execute();
        }
        
        $uniqueUser = (bool)$this->db()->select('id')
            ->from('visits_stats')
            ->where(array('ip_address' => $row['ip'], 'date' => $date->format('Y-m-d')))
            ->execute()
            ->fetchCell();
        
        if (!$uniqueUser) {
            $this->db()->insert('visits_stats')
                ->values(array('ip_address' => $row['ip'], 'date' => $date->format('Y-m-d')))
                ->execute();
        }
    }
}
