<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Mappers;

use Ilch\Date;
use Modules\Calendar\Models\Calendar as EntriesModel;

class Calendar extends \Ilch\Mapper
{
    public $tablename = 'calendar';

    /**
     * returns if the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['c.id' => 'DESC'], \Ilch\Pagination $pagination = null): ?array
    {
        $read_access = '';
        if (isset($where['ra.group_id'])) {
            $read_access = $where['ra.group_id'];
            unset($where['ra.group_id']);
        }

        $select = $this->db()->select();
        $select->fields(['c.id', 'c.uid', 'c.title', 'c.place', 'c.start', 'c.end', 'c.text', 'c.color', 'c.period_type', 'c.period_day', 'c.repeat_until', 'c.read_access_all'])
            ->from(['c' => $this->tablename])
            ->join(['ra' => 'calendar_access'], 'c.id = ra.calendar_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($read_access ? [$select->orX(['ra.group_id' => $read_access, 'c.read_access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['c.id']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Calendar entries.
     *
     * @param array $where
     * @return null|array
     */
    public function getEntries(array $where = []): ?array
    {
        return $this->getEntriesBy($where, []);
    }

    /**
     * Gets calendar.
     *
     * @param EntriesModel|int $id
     * @return EntriesModel|null
     */
    public function getCalendarById($id): ?EntriesModel
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        $entry = $this->getEntriesBy(['c.id' => (int)$id], []);

        if (!empty($entry)) {
            return reset($entry);
        }

        return null;
    }

    /**
     * Gets the Calendar entries by start and end.
     *
     * @param \Ilch\Date|string $start
     * @param \Ilch\Date|string $end
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return array|null
     */
    public function getEntriesForJson($start, $end, $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        if ($start && $end) {
            if (!is_a($start, \Ilch\Date::class)) {
                $start = new \Ilch\Date($start);
            }
            if (!is_a($end, \Ilch\Date::class)) {
                $end = new \Ilch\Date($end);
            }
            $select = $this->db()->select();
            return $this->getEntriesBy(
                [
                    $select->orX(
                        [
                            $select->andX(['c.end <=' => $end->format('Y-m-d').' 23:59:59']),
                            $select->andX(['c.start >=' => $start->format('Y-m-d').' 00:00:00', 'c.end <=' => $end->format('Y-m-d').' 23:59:59']),
                            $select->andX(
                                [
                                    'c.period_type !=' => '',
                                    'c.start <=' => $end->format('Y-m-d').' 00:00:00',
                                    $select->orX(['c.end >=' => $start->format('Y-m-d').' 23:59:59', 'c.end =' => '1000-01-01 00:00:00'])
                                ]
                            )
                        ]
                    ),
                    'ra.group_id' => $groupIds
                ],
                ['c.start' => 'ASC']
            );
        } else {
            return null;
        }
    }

    /**
     * Inserts Events model.
     *
     * @param EntriesModel $model
     * @return int
     */
    public function save(EntriesModel $model): int
    {
        $fields = $model->getArray();

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveReadAccess($result, $model->getReadAccess());

        return $result;
    }

    /**
     * Update the entries for which user groups are allowed to read a Calendar.
     *
     * @param int $calendarId
     * @param string|array $readAccess example: "1,2,3"
     * @param bool $addAdmin
     * @since 1.7.0
     */
    public function saveReadAccess(int $calendarId, $readAccess, bool $addAdmin = true)
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }

        // Delete possible old entries to later insert the new ones.
        $this->db()->delete($this->tablename.'_access')
            ->where(['calendar_id' => $calendarId])
            ->execute();

        $sql = 'INSERT INTO [prefix]_'.$this->tablename.'_access (calendar_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        $groupIds = [];
        if (!empty($readAccess)) {
            if (!in_array('all', $readAccess)) {
                $groupIds = $readAccess;
            }
        }
        if ($addAdmin && !in_array('1', $groupIds)) {
            $groupIds[] = '1';
        }

        foreach ($groupIds as $groupId) {
            // There is a limit of 1000 rows per insert, but according to some benchmarks found online
            // the sweet spot seams to be around 25 rows per insert. So aim for that.
            if ($rowCount >= 25) {
                $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                $this->db()->queryMulti($sqlWithValues);
                $rowCount = 0;
                $sqlWithValues = $sql;
            }

            $rowCount++;
            $sqlWithValues .= '(' . $calendarId . ',' . (int)$groupId . '),';
        }

        // Insert remaining rows.
        $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
        $this->db()->queryMulti($sqlWithValues);
    }

    /**
     * @param EntriesModel|int $id
     * @return bool
     */
    public function delete($id): bool
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Return start- and enddate for next recurrence.
     *
     * @param string $type
     * @param Date $startdate
     * @param Date $enddate
     * @param Date $untilDate
     * @param int $factor
     * @return array
     */
    public function repeat(string $type, \Ilch\Date $startdate, \Ilch\Date $enddate, \Ilch\Date $untilDate, int $factor = 1): array
    {
        $recurrences = [];
        $startdateRecurrence = clone $startdate;
        $enddateRecurrence = clone $enddate;

        while($startdateRecurrence <= $untilDate) {
            $event = [];
            switch ($type) {
                case 'monthly':
                    $startdateRecurrence->modify($factor . ' months');
                    $enddateRecurrence->modify($factor . ' months');
                    break;
                case 'daily':
                    $startdateRecurrence->modify($factor . ' days');
                    $enddateRecurrence->modify($factor . ' days');
                    break;
                case 'weekly':
                    $startdateRecurrence->modify($factor . ' weeks');
                    $enddateRecurrence->modify($factor . ' weeks');
                    break;
                case 'quarterly':
                    // work with a factor and 3 months as a quarter of a year is 3 months?
                    $startdateRecurrence->modify(($factor * 3). ' months');
                    $enddateRecurrence->modify(($factor * 3). ' months');
                    break;
                case 'yearly':
                    $startdateRecurrence->modify($factor . ' years');
                    $enddateRecurrence->modify($factor . ' years');
                    break;
                case 'days':
                    $days = [
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        7 => 'Sunday'
                    ];

                    $previousStartdate = clone $startdateRecurrence;

                    $startdateRecurrence->modify('next ' . $days[$factor] . ' ' . $previousStartdate->format('H:i:s'));
                    $daydiff = $previousStartdate->diff($startdateRecurrence, true);
                    $enddateRecurrence->add($daydiff);
                    break;
            }

            if ($startdateRecurrence <= $untilDate) {
                $event['start'] = clone $startdateRecurrence;
                $event['end'] = clone $enddateRecurrence;
                $recurrences[] = $event;
            }
        }

        return $recurrences;
    }
}
