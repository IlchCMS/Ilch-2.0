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
     * Gets all logs, optionally filtered by date prefix (e.g. '2024' or '2024-01').
     *
     * @param string $date
     * @return LogsModel[]|null
     */
    public function getLogs($date = '')
    {
        $select = $this->db()->select('*')
            ->from('logs');

        // Only add the date filter if a date was actually given,
        // so an empty string doesn't silently match everything via LIKE '%'.
        if ($date !== '') {
            $select->where(['date LIKE' => $date . '%']);
        }

        $entriesArray = $select
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
     * Gets all logs dates.
     *
     * @return LogsModel[]|null
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
     * @return LogsModel[]
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
        $now = new IlchDate();

        $oneMinuteAgo = new IlchDate();
        $oneMinuteAgo->modify('-1 minutes');

        $count = $this->db()->select('COUNT(*)')
            ->from('logs')
            ->where(['user_id' => (int)$userId, 'info' => $info, 'date >' => $oneMinuteAgo->toDb(true)])
            ->execute()
            ->fetchCell();

        if ($count == 0) {
            $this->db()->insert('logs')
                ->values([
                    'user_id' => $userId,
                    'info' => $info,
                    'date' => $now->toDb(true)
                ])
                ->execute();
        }
    }

    /**
     * Clear log.
     */
    public function clearLog()
    {
        $this->db()->truncate('[prefix]_logs');
    }
}
