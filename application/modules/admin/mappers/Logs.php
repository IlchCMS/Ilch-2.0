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
    public function getLogs(string $date = ''): ?array
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
     * Gets all log dates.
     *
     * @return LogsModel[]|null
     */
    public function getLogsDate(): ?array
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
     * Gets the logs by the optionally provided where clause.
     *
     * @param array $where
     * @return LogsModel[]
     */
    public function getLogsBy(array $where = []): array
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
     * Inserts a log.
     *
     * Skipped if the same log for the same user was already written less than a minute ago.
     *
     * @param int $userId
     * @param string $info
     */
    public function saveLog(int $userId, string $info): void
    {
        $now = new IlchDate();

        $oneMinuteAgo = new IlchDate();
        $oneMinuteAgo->modify('-1 minutes');

        $count = $this->db()->select('COUNT(*)')
            ->from('logs')
            ->where(['user_id' => $userId, 'info' => $info, 'date >' => $oneMinuteAgo->toDb(true)])
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
     * Clears all logs.
     */
    public function clearLog(): void
    {
        $this->db()->truncate('[prefix]_logs');
    }
}
