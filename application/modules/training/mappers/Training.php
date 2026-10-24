<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Mappers;

use DateInterval;
use DatePeriod;
use Ilch\Date;
use Ilch\Pagination;
use Modules\Training\Models\Training as TrainingModel;

class Training extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public string $tablename = 'training';

    /**
     * @var string
     */
    public string $tablenameAccess = 'training_access';

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
        if (isset($where['ra.read_access'])) {
            $access = $where['ra.read_access'];
            unset($where['ra.read_access']);
        }

        $select = $this->db()->select();
        $select->fields(['t.id', 't.title', 't.date', 't.end', 't.period_type', 't.period_day', 't.repeat_until', 't.place', 't.contact', 't.voice_server', 't.voice_server_ip', 't.voice_server_pw', 't.game_server', 't.game_server_ip', 't.game_server_pw', 't.text', 't.show', 't.access_all'])
            ->from(['t' => $this->tablename])
            ->join(['ra' => $this->tablenameAccess], 't.id = ra.training_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($access ? [$select->orX(['ra.group_id' => $access, 't.access_all' => '1'])] : [])))
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
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel[]|null
     */
    public function getTraining(array $where = [], $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge($where, ($groupIds ? ['ra.read_access' => $groupIds] : [])));
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
     * @param Date|string|null $start Y-m-d H:m:i
     * @param Date|string|null $end Y-m-d H:m:i
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel[]|null
     */
    public function getTrainingsForJson($start = null, $end = null, $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        if ($start && $end) {
            if (!is_a($start, Date::class)) {
                $start = new Date($start);
            }
            if (!is_a($end, Date::class)) {
                $end = new Date($end);
            }

            $select = $this->db()->select();

            // One-off trainings that start within the given range, plus recurrent
            // trainings that started before the range but still run within it
            // (or have no end date).
            $where = [
                $select->orX(
                    [
                        $select->andX(['t.date >=' => $start->format('Y-m-d') . ' 00:00:00', 't.end <=' => $end->format('Y-m-d') . ' 23:59:59']),
                        $select->andX(
                            [
                                't.period_type !=' => '',
                                't.date <=' => $end->format('Y-m-d') . ' 00:00:00',
                                $select->orX(['t.end >=' => $start->format('Y-m-d') . ' 23:59:59', 't.end =' => '1000-01-01 00:00:00'])
                            ]
                        )
                    ]
                )
            ];

            // Use the ra.read_access key so that getEntriesBy() applies the
            // access_all fallback, consistent with getTraining()/getEntriesBy().
            if ($groupIds) {
                $where['ra.read_access'] = $groupIds;
            }

            return $this->getEntriesBy($where);
        }

        return null;
    }

    /**
     * Get the next trainings.
     *
     * @param int|null $limit
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return TrainingModel[]|null
     * @since Version 1.10.0
     */
    public function getNextTrainings(?int $limit = null, $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $pagination = null;

        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRowsPerPage($limit);
        }

        $currentDate = new Date();

        // Get all recurrent trainings with a repeat until date not in the past.
        $where = [
            't.period_type !=' => '',
            't.repeat_until >' => $currentDate,
            'ra.read_access' => $groupIds
        ];
        $recurrentTrainings = $this->getEntriesBy($where) ?? [];

        // Calculate next training dates for the recurrent trainings.
        foreach ($recurrentTrainings as $training) {
            $this->calculateNextTrainingDate($training);
        }

        // Get the next trainings. Taking the limit into account.
        $where = [
            't.period_type =' => '',
            't.date >=' => $currentDate,
            'ra.read_access' => $groupIds
        ];

        $trainings = array_merge($recurrentTrainings, $this->getEntriesBy($where, ['t.date' => 'ASC'], $pagination) ?? []);

        // Sort all trainings by date.
        usort($trainings, fn(TrainingModel $a, TrainingModel $b) => strcmp($a->getDate(), $b->getDate()));

        // If there is a limit then only return the number of trainings according to the limit as there
        // might be more results than needed.
        if ($limit) {
            return array_slice($trainings, 0, $limit);
        }

        return $trainings;
    }

    /**
     * Gets the calculated countdown until the given date.
     *
     * @param Date $countdown_date The start date of the training
     * @param int $countdown_time The "live" window in minutes after the start
     * @return bool|string Countdown like "1d 2h", "3h 5m" or "5m"; "live" if the
     *                     training started within the last $countdown_time minutes;
     *                     false if it started longer ago (i.e. it is not ongoing anymore)
     */
    public function countdown(Date $countdown_date, int $countdown_time = 60)
    {
        $datenow = new Date();
        $difference = $countdown_date->getTimestamp() - $datenow->getTimestamp();

        // The training already started. If it started within the countdown window,
        // it is considered to be live. If it started longer than the countdown
        // window ago, it has ended, so nothing is displayed.
        if ($difference < 0) {
            if ($difference <= (-(60 * $countdown_time))) {
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
     * Calculate the next training date. Updates the passed parameter.
     *
     * @param TrainingModel $training
     * @return void
     * @since Version 1.10.0
     */
    public function calculateNextTrainingDate(TrainingModel $training)
    {
        // Early return if this is not a recurrent training.
        if ($training->getPeriodType() === '') {
            return;
        }

        $givenDate = new Date();
        $begin = new Date($training->getDate());

        // Early return if the date is still in the future or if the repeat until date is in the past.
        if ($begin > $givenDate || ($givenDate > new Date($training->getRepeatUntil()))) {
            return;
        }

        $factor = (int)$training->getPeriodDay();
        $interval = null;

        switch ($training->getPeriodType()) {
            case 'monthly':
                $interval = DateInterval::createFromDateString($factor . ' months');
                break;
            case 'daily':
                $interval = DateInterval::createFromDateString($factor . ' days');
                break;
            case 'weekly':
                $interval = DateInterval::createFromDateString($factor . ' weeks');
                break;
            case 'quarterly':
                // work with a factor and 3 months as a quarter of a year is 3 months.
                $interval = DateInterval::createFromDateString(($factor * 3) . ' months');
                break;
            case 'yearly':
                $interval = DateInterval::createFromDateString($factor . ' years');
                break;
            case 'days':
                // Recurs on a fixed weekday: step in one-week intervals, anchored to
                // the most recent occurrence of that weekday at the original time of
                // day (DateInterval cannot express "next Monday").
                if ($factor < 1 || $factor > 7) {
                    return;
                }

                $anchor = new Date();
                $anchor->setTime((int)$begin->format('G'), (int)$begin->format('i'), (int)$begin->format('s'));
                while ((int)$anchor->format('N') !== $factor) {
                    $anchor->modify('-1 day');
                }
                $begin = $anchor;
                $interval = DateInterval::createFromDateString('1 weeks');
                break;
        }

        // Calculate the date for the next recurring training.
        $end = new Date($training->getRepeatUntil());
        $datePeriod = new DatePeriod($begin, $interval, $end);
        $givenDate = new Date();

        foreach ($datePeriod as $date) {
            if ($date < $givenDate) {
                continue;
            }

            $training->setDate($date->format('Y-m-d H:i:s'));
            break;
        }

        // Calculate the end date for the next recurring training. The end date has to
        // belong to the (new) training, so it must not be before the new start date.
        $begin = new Date($training->getEnd());
        $datePeriod = new DatePeriod($begin, $interval, $end);
        $nextTrainingDate = new Date($training->getDate());

        foreach ($datePeriod as $date) {
            if ($date < $nextTrainingDate) {
                continue;
            }

            $training->setEnd($date->format('Y-m-d H:i:s'));
            break;
        }
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
            $training->setId($itemId);
        }

        $this->saveAccess($itemId, $training->getReadAccess());

        return $itemId;
    }

    /**
     * Update the entries for which user groups are allowed to read a Cat.
     *
     * @param int $trainingId
     * @param string|array $access example: "1,2,3"
     * @param bool $addAdmin
     */
    public function saveAccess(int $trainingId, $access, bool $addAdmin = true)
    {
        if (\is_string($access)) {
            // An empty string means "no groups" instead of a single empty group.
            $access = ($access === '') ? [] : explode(',', $access);
        } elseif (!\is_array($access)) {
            $access = [];
        }

        // Drop empty values (e.g. from "1,,2") so no group with id 0 is inserted.
        $access = array_values(array_filter($access, fn ($groupId) => $groupId !== '' && $groupId !== null));

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
