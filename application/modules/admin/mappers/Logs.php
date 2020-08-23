<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Logs as LogsModel;
use Ilch\Date as IlchDate;

class Logs extends \Ilch\Mapper
{
    /**
     * Gets all logs.
     *
     * @param string $date
     * @return LogsModel[]|array
     */
    public function getLogs($date)
    {
        $entriesArray = $this->db()->select('*')
            ->from('logs')
            ->where(['date LIKE' => $this->db()->escape($date . '%')])
            ->order(['date' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($entriesArray)) {
            return null;
        }

        $logs = [];
        foreach ($entriesArray as $entry) {
            $model = new LogsModel();
            $model->setUserId($entry['user_id']);
            $model->setDate($entry['date']);
            $model->setInfo($entry['info']);
            $logs[] = $model;
        }

        return $logs;
    }

    /**
     * Gets all logs date.
     *
     * @return LogsModel[]|array
     */
    public function getLogsDate()
    {
        $sql = 'SELECT DATE(`date`) AS `date_full`, MONTH(`date`) AS `date_month`, DAY(`date`) AS `date_day`
                FROM `[prefix]_logs`
                GROUP BY `date_full`, `date_month`, `date_day`
                ORDER BY `date_full` DESC';
        $entriesArray = $this->db()->queryArray($sql);

        if (empty($entriesArray)) {
            return null;
        }

        $logs = [];
        foreach ($entriesArray as $entry) {
            $model = new LogsModel();
            $model->setDate($entry['date_full']);
            $logs[] = $model;
        }

        return $logs;
    }

    /**
     * Get the logs by an optionally provided where-clause.
     *
     * @param array $where
     * @return array|LogsModel[]
     */
    public function getLogsBy($where = [])
    {
        $entriesArray = $this->db()->select('*')
            ->from('logs')
            ->where($where)
            ->order(['date' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($entriesArray)) {
            return [];
        }

        $logs = [];
        foreach ($entriesArray as $entry) {
            $model = new LogsModel();
            $model->setUserId($entry['user_id']);
            $model->setDate($entry['date']);
            $model->setInfo($entry['info']);
            $logs[] = $model;
        }

        return $logs;
    }

    /**
     * Insert log.
     *
     * @param int $userId
     * @param string $info
     */
    public function saveLog($userId, $info)
    {
        $date = new IlchDate();
        $date->modify('-1 minutes');

        $count = $this->db()->select('COUNT(*)')
            ->from('logs')
            ->where(['user_id' => intval($userId), 'info' => $this->db()->escape($info), 'date >' => $date->toDb(true)])
            ->execute()
            ->fetchCell();

        if ($count == 0) {
            $fields = [
                'user_id' => $userId,
                'info' => $info
            ];

            $this->db()->insert('logs')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Clear log.
     *
     */
    public function clearLog()
    {
        $this->db()->truncate('[prefix]_logs');
    }
}
