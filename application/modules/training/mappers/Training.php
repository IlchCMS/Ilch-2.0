<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Mappers;

use Ilch\Pagination;
use Modules\Training\Models\Training as TrainingModel;

class Training extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'training';

    /**
     * @var string
     */
    public $tablenameAccess = 'training_access';

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return TrainingModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['t.date' => 'ASC'], ?Pagination $pagination = null): ?array
    {
        $access = '';
        if (isset($where['ta.read_access'])) {
            $access = $where['ra.read_access'];
            unset($where['ta.read_access']);
        }

        $select = $this->db()->select();
        $select->fields(['t.id', 't.title', 't.date', 't.time', 't.place', 't.contact', 't.voice_server', 't.voice_server_ip', 't.voice_server_pw', 't.game_server', 't.game_server_ip', 't.game_server_pw', 't.text', 't.show', 't.access_all'])
            ->from(['t' => $this->tablename])
            ->join(['ra' => $this->tablenameAccess], 't.id = ra.training_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($access ? [$select->orX(['ta.group_id' => $access, 't.access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['t.id']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entry) {
            $entryModel = new TrainingModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Training.
     *
     * @param array $where
     *  @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel[]|null
     */
    public function getTraining(array $where = [], $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge($where, ($groupIds ? ['ta.group_id' => $groupIds] : [])));
    }

    /**
     * Gets training by ID.
     *
     * @param int $id
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel|null
     */
    public function getTrainingById(int $id, $groupIds = '3'): ?TrainingModel
    {
        $entries = $this->getTraining(['t.id' => $id], $groupIds);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Get the trainings between start and end.
     *
     * @param string|null $start Y-m-d H:m:i
     * @param string|null $end Y-m-d H:m:i
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel[]|null
     */
    public function getTrainingsForJson(?string $start, ?string $end, $groupIds = '3'): ?array
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            return $this->getTraining(['t.date >=' => $start, 't.date <=' => $end, 't.show' => 1], $groupIds);
        } else {
            return null;
        }
    }

    /**
     * Gets Trainings by limit
     *
     * @param int|null $limit
     *  @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $order
     * @return TrainingModel[]|null
     */
    public function getTrainingsListWithLimt(?int $limit = null, $groupIds = '3', string $order = 'ASC'): ?array
    {
        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRows($limit);
        }

        return $this->getEntriesBy(($groupIds ? ['ta.group_id' => $groupIds] : []), ['t.date' => $order], $pagination ?? null);
    }

    /**
     * Gets the calculated Countdown
     *
     * @param \Ilch\Date $countdown_date
     * @param int $countdown_time
     * @return bool|string
     */
    public function countdown(\Ilch\Date $countdown_date, int $countdown_time = 60)
    {
        $date = new \Ilch\Date();
        $datenow = new \Ilch\Date($date->format("Y-m-d H:i:s", true));
        $difference = $countdown_date->getTimestamp() - $datenow->getTimestamp();
        if ($difference < 0) {
            if ($difference <= (60 * $countdown_time)) {
                return false;
            }
            $difference = 0;
        }

        $days_left = floor($difference / 60 / 60 / 24);
        $hours_left = floor(($difference - $days_left * 60 * 60 * 24) / 60 / 60);
        $minutes_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60) / 60);
        // OUTPUT
        if ($days_left == '0') {
            if ($hours_left == '0' && $minutes_left > '0') {
                return $minutes_left . 'm';
            }

            if ($hours_left == '0' && $minutes_left == '0') {
                return 'live';
            }

            return $hours_left . 'h ' . $minutes_left . 'm';
        }

        return $days_left . 'd ' . $hours_left . 'h';
    }

    /**
     * Inserts or updates training model.
     *
     * @param TrainingModel $training
     * @return int
     */
    public function save(TrainingModel $training): int
    {
        $fields = $training->getArray(false);
        if ($training->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $training->getId()])
                ->execute();
            $itemId = $training->getId();
        } else {
            $itemId = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveAccess($itemId, $training->getReadAccess());

        return $itemId;
    }

    /**
     * Update the entries for which user groups are allowed to read a Cat.
     *
     * @param int $trainingId
     * @param string|array $access example: "1,2,3"
     * @param boolean $addAdmin
     */
    public function saveAccess(int $trainingId, $access, bool $addAdmin = true)
    {
        if (\is_string($access)) {
            $access = explode(',', $access);
        }

        // Delete possible old entries to later insert the new ones.
        $this->db()->delete($this->tablenameAccess)
            ->where(['training_id' => $trainingId])
            ->execute();

        $groupIds = [];
        if (!empty($access)) {
            if (!in_array('all', $access)) {
                $groupIds = $access;
            }
        }
        if ($addAdmin && !in_array('1', $groupIds)) {
            $groupIds[] = '1';
        }

        $preparedRows = [];
        foreach ($groupIds as $groupId) {
            $preparedRows[] = [$trainingId, (int)$groupId];
        }

        if (count($preparedRows)) {
            // Add access rights in chunks of 25 to the table. This prevents reaching the limit of 1000 rows
            $chunks = array_chunk($preparedRows, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert($this->tablenameAccess)
                    ->columns(['training_id', 'group_id'])
                    ->values($chunk)
                    ->execute();
            }
        }
    }

    /**
     * Check if table exists.
     *
     * @param $table
     * @return false|true
     * @throws \Ilch\Database\Exception
     */
    public function existsTable($table): bool
    {
        return $this->db()->ifTableExists($table);
    }

    /**
     * Deletes training with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
