<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\War as EntriesModel;
use Modules\Comment\Mappers\Comment as Comments;

class War extends \Ilch\Mapper
{
    public $tablename = 'war';

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
    public function getEntriesBy($where = [], $orderBy = ['g.id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['w.id', 'w.enemy', 'w.group', 'w.time', 'w.maps', 'w.server', 'w.password', 'w.xonx', 'w.game', 'w.matchtype', 'w.report', 'w.status', 'w.show', 'w.lastaccepttime'])
            ->from(['w' => $this->tablename])
            ->join(['ra' => 'war_access'], 'w.id = ra.war_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['g' => 'war_groups'], 'w.group = g.id', 'LEFT', ['war_groups' => 'g.tag', 'group_name' => 'g.name', 'group_is' => 'g.id'])
            ->join(['e' => 'war_enemy'], 'w.enemy = e.id', 'LEFT', ['war_enemy' => 'e.tag', 'enemy_name' => 'e.name', 'enemy_id' => 'e.id'])
            ->where($where)
            ->order($orderBy)
            ->group(['w.id']);

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
     * Gets the Wars.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getWars($where = [], $pagination = null)
    {
        return $this->getEntriesBy($where, ['w.time' => 'ASC'], $pagination);
    }

    /**
     * Gets the Next Wars.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getNextWars($where = [], $pagination = null)
    {
        return $this->getEntriesBy($where, ['w.id' => 'DESC'], $pagination);
    }

    /**
     * Gets war by id.
     *
     * @param int|EntriesModel $id
     * @return EntriesModel|null
     */
    public function getWarById($id)
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        $entrys = $this->getEntriesBy(['w.id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Get wars for json (for example the calendar)
     *
     * @param string $start
     * @param string $end
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return array|null
     */
    public function getWarsForJson(string $start, string $end, $groupIds = '3')
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);

            $entryArray = $this->getEntriesBy(['show' => 1, 'time >=' => $start, 'time <=' => $end, 'ra.group_id' => $groupIds], []);

            if (empty($entryArray)) {
                return [];
            }

            return $entryArray;
        } else {
            return null;
        }
    }

    /**
     * Gets wars by where
     *
     * @param null|array $where
     * @param int $pagination
     * @return array|null
     */
    public function getWarsByWhere($where = null, $pagination = null)
    {
        return $this->getEntriesBy($where, ['w.time' => 'DESC'], $pagination);
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return integer
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
            $result = (int)$this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveReadAccess($result, $model->getReadAccess());

        return $result;
    }

    /**
     * Update the entries for which user groups are allowed to read an War.
     *
     * @param int $warId
     * @param string|array $readAccess example: "1,2,3"
     * @since 1.15.0
     */
    public function saveReadAccess(int $warId, $readAccess, bool $addAdmin = true)
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }
        
        // Delete possible old entries to later insert the new ones.
        $this->db()->delete('war_access')
            ->where(['war_id' => $warId])
            ->execute();

        $sql = 'INSERT INTO [prefix]_war_access (war_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        if (!empty($readAccess)) {
            $groupIds = explode(',', $readAccess);
        } else {
            $groupIds = [];
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
            $sqlWithValues .= '(' . (int)$warId . ',' . (int)$groupId . '),';
        }

        // Insert remaining rows.
        $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
        $this->db()->queryMulti($sqlWithValues);
    }

    /**
     * Deletes the entry.
     *
     * @param int|EntriesModel $id
     * @return boolean
     */
    public function delete($id): bool
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }
        
        $comments = new Comments();
        $comments->deleteByKey('war/index/show/id/'.(int)$id);

        return $this->db()->delete($this->tablename)
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Get a list of wars by status.
     *
     * @param null|int $status
     * @param null $pagination
     * @return array|null
     */
    public function getWarListByStatus($status = null, $pagination = null)
    {
        return $this->getEntriesBy(['status' => $this->db()->escape($status)], ['w.time' => 'DESC'], $pagination);
    }

    /**
     * Get a list of wars.
     *
     * @param null $pagination
     * @param string|array $readAccess A string like '1,2,3' or an array like [1,2,3]
     * @return array|null
     */
    public function getWarList($pagination = null, $readAccess = '3', int $groupId = null)
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }

        return $this->getEntriesBy(array_merge(['ra.group_id' => $readAccess], ($groupId ? ['w.group' => $groupId] : [])), ['w.time' => 'DESC'], $pagination);
    }

    /**
     * Gets a number of wars to show them for example in a box.
     *
     * @param null|int $status
     * @param null|int $limit
     * @param string|array $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @param string $order
     * @return array|null
     */
    public function getWarListByStatusAndLimt($status = null, $limit = null, $groupIds = '3', $order = 'ASC')
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(['status' => $this->db()->escape($status), 'ra.group_id' => $groupIds], ['w.time' => $order]);
    }

    public function getWarOptDistinctXonx()
    {
        $sql = 'SELECT DISTINCT xonx
                FROM [prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setWarXonx($entries['xonx']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarOptDistinctGame()
    {
        $sql = 'SELECT DISTINCT game
                FROM [prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setWarGame($entries['game']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarOptDistinctMatchtype()
    {
        $sql = 'SELECT DISTINCT matchtype
                FROM [prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Returns the Countdown
     *
     * @param \Ilch\Date|string $datum
     * @return string
     */
    public function countdown($datum): string
    {
        $datenow = new \Ilch\Date();
        $datenow = $datenow->format(null, true);
        
        if (is_a($datum, \Ilch\Date::class)) {
            $date = $datum;
        } else {
            $date = new \Ilch\Date($datum);
        }

        // make a unix timestamp for the given date
        $the_countdown_date = $date->getTimestamp();

        // get current unix timestamp
        $today = strtotime($datenow);

        $difference = $the_countdown_date - $today;
        if ($difference < 0) {
            $difference = 0;
        }

        $days_left = floor($difference / 60 / 60 / 24);
        $hours_left = floor(($difference - $days_left * 60 * 60 * 24) / 60 / 60);
        $minutes_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60) / 60);

        // OUTPUT
        $out = '';
        if ($days_left == '0') {
            if ($hours_left == '0' && $minutes_left > '0') {
                $out .= $minutes_left . 'm';
            } elseif ($hours_left == '0' && $minutes_left == '0') {
                $out .= 'live';
            } else {
                $out .= $hours_left . 'h ' . $minutes_left . 'm';
            }
        } else {
            $out .= $days_left . 'd ' . $hours_left . 'h';
        }
        return $out;
    }

    /**
     * Check if table exists.
     *
     * @param string $table
     * @return boolean
     */
    public function existsTable(string $table): bool
    {
        return $this->db()->ifTableExists($table);
    }

    /**
     * Updates Entrie status with given id.
     *
     * @param int|EntriesModel $id
     * @param int $status
     * @return boolean
     */
    public function updateStatus($id, int $status = -1): bool
    {
        if ($status !== -1) {
            $statusNow = $status;
        } else {
            if (is_a($id, EntriesModel::class)) {
                $status = $id->getStatus();
            } else {
                $status = (int) $this->db()->select('status')
                                ->from($this->tablename)
                                ->where(['id' => (int)$id])
                                ->execute()
                                ->fetchCell();
            }

            if ($status === 1) {
                $statusNow = 0;
            } else {
                $statusNow = 1;
            }
        }
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['status' => $statusNow])
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Updates Entrie show with given id.
     *
     * @param int|EntriesModel $id
     * @param int $show
     * @return boolean
     */
    public function updateShow($id, int $show = -1): bool
    {
        if ($show !== -1) {
            $showNow = $show;
        } else {
            if (is_a($id, EntriesModel::class)) {
                $show = $id->getStatus();
            } else {
                $show = (int) $this->db()->select('show')
                                ->from($this->tablename)
                                ->where(['id' => (int)$id])
                                ->execute()
                                ->fetchCell();
            }

            if ($show === 1) {
                $showNow = 2;
            } else {
                $showNow = 1;
            }
        }
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['show' => $showNow])
            ->where(['id' => (int)$id])
            ->execute();
    }
}
