<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Mappers;

use Modules\Calendar\Models\Calendar as EntriesModel;

class Calendar extends \Ilch\Mapper
{
    public $tablename = 'calendar';

    /**
     * returns if the module is installed.
     *
     * @return boolean
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
        $select->fields(['c.id', 'c.title', 'c.place', 'c.start', 'c.end', 'c.text', 'c.color', 'c.period_type', 'c.period_day', 'c.read_access_all'])
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

        $entrys = $this->getEntriesBy(['c.id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
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
     * @param boolean $addAdmin
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
     * @param EntriesModel|integer $id
     * @return boolean
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
     * Repeat Date Entry .
     *
     * @param String $type
     * @param \Ilch\Date $startdate
     * @param \Ilch\Date|int $enddate
     * @param int $faktor
     * @return array
     */
    public function repeat(String $type, \Ilch\Date $startdate, $enddate, int $faktor = 1)
    {
        //type=daily/weekly/monthly_date/monthly_day/quarterly_date/quarterly_day - $start=yyyy-mm-dd hh-mm-ss - $end=yyyy-mm-dd hh-mm-ss/xx
        $a = [];
        $type = trim($type);
        switch ($type) {
            case "monthly":
            case "daily":
                $diff = 1;
                break;
            case "weekly":
                $diff = 7;
                break;
            case "quarterly":
                $diff = 3;
                break;
            case "days":
                $daydiff = $faktor - $startdate->format('N');
                if ($daydiff < 0) {
                    $daydiff = 7 - $daydiff;
                }
                if ($daydiff < 7) {
                    $startdate->modify('+' . $daydiff . ' day');
                }

                $diff = 7;
                $faktor = 1;
                break;
            default:
                $diff = 0;
        }
        if ($diff > 0 && $faktor > 0) {
            if (is_numeric($enddate)) {
                $temp = clone $startdate;

                switch ($type) {
                    case "weekly":
                        $temp->modify(($diff * $faktor * 7) . " days");
                        break;
                    case "daily":
                        $temp->modify(($diff * $faktor * 30) . " days");
                        break;
                    case "quarterly":
                    case "monthly":
                        $temp->modify(($diff * $faktor * 2 ) . " months");
                        break;
                    case "days":
                        $temp->modify(($diff * 7)." days");
                        break;
                }
                $enddate = $temp;
            }
            if (is_a($enddate, \Ilch\Date::class)) {
                $end_ts = $enddate->getTimestamp();
                $a[] = $startdate;
                $temp = clone $startdate;
                while ($temp->getTimestamp() < $end_ts) {
                    $temp = clone $temp;
                    switch ($type) {
                        case "weekly":
                        case "days":
                        case "daily":
                            $temp->modify(($diff * $faktor) . " days");
                            break;
                        case "quarterly":
                        case "monthly":
                            $temp->modify(($diff * $faktor) . " months");
                            break;
                    }
                    if ($temp->getTimestamp() < $end_ts) {
                        $a[] = $temp;
                    }
                }
            } else {
                $a[] = $startdate;
            }
        } else {
            $a[] = $startdate;
        }
        return $a;
    }
}
