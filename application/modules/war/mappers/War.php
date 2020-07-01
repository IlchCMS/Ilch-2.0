<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\War as WarModel;

class War extends \Ilch\Mapper
{
    /**
     * Gets the Wars.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return WarModel[]|array
     */
    public function getWars($where = [], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war')
            ->where($where)
            ->order(['time' => 'ASC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['id'])
                ->setWarEnemy($entries['enemy'])
                ->setWarGroup($entries['group'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Next Wars.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return WarModel[]|array
     */
    public function getNextWars($where = [], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war')
            ->where($where)
            ->order(['id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['id'])
                ->setWarEnemy($entries['enemy'])
                ->setWarGroup($entries['group'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets war by id.
     *
     * @param integer $id
     * @return WarModel|null
     */
    public function getWarById($id)
    {
        $warRow = $this->db()->select('*')
            ->from('war')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($warRow)) {
            return null;
        }

        $warModel = new WarModel();
        $warModel->setId($warRow['id'])
            ->setWarEnemy($warRow['enemy'])
            ->setWarGroup($warRow['group'])
            ->setWarTime($warRow['time'])
            ->setWarMaps($warRow['maps'])
            ->setWarServer($warRow['server'])
            ->setWarPassword($warRow['password'])
            ->setWarXonx($warRow['xonx'])
            ->setWarGame($warRow['game'])
            ->setWarMatchtype($warRow['matchtype'])
            ->setWarReport($warRow['report'])
            ->setWarStatus($warRow['status'])
            ->setShow($warRow['show'])
            ->setReadAccess($warRow['read_access']);

        return $warModel;
    }

    /**
     * Get wars for json (for example the calendar)
     *
     * @param $start
     * @param $end
     * @return array|array[]|null
     */
    public function getWarsForJson($start, $end)
    {
        if ($start && $end) {
            $start = new \Ilch\Date($start);
            $end = new \Ilch\Date($end);
            $entryArray = $this->db()->select()
                ->fields(['w.id', 'w.time', 'w.show', 'w.read_access'])
                ->from(['w' => 'war'])
                ->join(['g' => 'war_groups'], 'w.group = g.id', 'LEFT', ['war_groups' => 'g.tag'])
                ->join(['e' => 'war_enemy'], 'w.enemy = e.id', 'LEFT', ['war_enemy' => 'e.tag'])
                ->where(['show' => 1, 'time >=' => $start, 'time <=' => $end])
                ->execute()
                ->fetchRows();
        } else {
            return null;
        }

        if (empty($entryArray)) {
            return [];
        }

        return $entryArray;
    }

    /**
     * Gets war by where
     *
     * @param mixed $where
     * @param int $pagination
     * @return WarModel[]|null
     * @throws \Ilch\Database\Exception
     */
    public function getWarsByWhere($where = null, $pagination = null)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,w.show,w.read_access,g.name as group_name,g.id as group_is,e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                WHERE w.'.$where.'
                ORDER by w.time DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $warArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id'])
                ->setWarEnemy($entries['enemy_name'])
                ->setWarGroup($entries['group_name'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Inserts or updates war entry.
     *
     * @param WarModel $model
     */
    public function save(WarModel $model)
    {
        $fields = [
            'enemy' => $model->getWarEnemy(),
            'group' => $model->getWarGroup(),
            'time' => $model->getWarTime(),
            'maps' => $model->getWarMaps(),
            'server' => $model->getWarServer(),
            'password' => $model->getWarPassword(),
            'xonx' => $model->getWarXonx(),
            'game' => $model->getWarGame(),
            'matchtype' => $model->getWarMatchtype(),
            'report' => $model->getWarReport(),
            'status' => $model->getWarStatus(),
            'show' => $model->getShow(),
            'read_access' => $model->getReadAccess()
        ];

        if ($model->getId()) {
            $this->db()->update('war')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('war')
                ->values($fields)
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('war')
            ->where(['id' => $id])
            ->execute();
    }

    public function getWarListByStatus($status = NULL, $pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,w.show,w.read_access,g.name as group_name,g.id as group_is,e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                WHERE status = "'.$status.'"
                ORDER by w.time DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $warArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id'])
                ->setWarEnemy($entries['enemy_name'])
                ->setWarGroup($entries['group_name'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarList($pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,w.show,w.read_access,
                g.name as group_name,g.id as group_is,
                e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                ORDER by w.time DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $warArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id'])
                ->setWarEnemy($entries['enemy_name'])
                ->setWarGroup($entries['group_name'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarListByStatusAndLimt($status = NULL, $limit = NULL, $order = 'ASC')
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,w.show,w.read_access,g.name as group_name,g.tag as group_tag,g.id as group_id,e.name as enemy_name,e.tag as enemy_tag,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                WHERE status = "'.$status.'"
                ORDER by w.time '.$order.'
                LIMIT '.$limit;

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = [];

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id'])
                ->setWarEnemy($entries['enemy_name'])
                ->setWarEnemyTag($entries['enemy_tag'])
                ->setWarGroup($entries['group_name'])
                ->setWarGroupTag($entries['group_tag'])
                ->setWarTime($entries['time'])
                ->setWarMaps($entries['maps'])
                ->setWarServer($entries['server'])
                ->setWarPassword($entries['password'])
                ->setWarXonx($entries['xonx'])
                ->setWarGame($entries['game'])
                ->setWarMatchtype($entries['matchtype'])
                ->setWarReport($entries['report'])
                ->setWarStatus($entries['status'])
                ->setShow($entries['show'])
                ->setReadAccess($entries['read_access']);
            $entry[] = $entryModel;
        }

        return $entry;
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
            $entryModel = new WarModel();
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
            $entryModel = new WarModel();
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
            $entryModel = new WarModel();
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function countdown($year, $month, $day, $hour, $minute)
    {
        $date = new \Ilch\Date();
        $date = $date->format(null, true);

        // make a unix timestamp for the given date
        $the_countdown_date = mktime($hour, $minute, 0, $month, $day, $year);

        // get current unix timestamp
        $today = strtotime($date);

        $difference = $the_countdown_date - $today;
        if ($difference < 0) {
            $difference = 0;
        }

        $days_left = floor($difference / 60 / 60 / 24);
        $hours_left = floor(($difference - $days_left * 60 * 60 * 24) / 60 / 60);
        $minutes_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60) / 60);

        // OUTPUT
        if ($days_left == '0') {
            if ($hours_left == '0' && $minutes_left > '0') {
                echo $minutes_left.'m';
            } elseif ($hours_left == '0' && $minutes_left == '0') {
                echo 'live';
            } else  {
                echo $hours_left.'h '.$minutes_left.'m';
            }
        } else {
            echo $days_left.'d '.$hours_left.'h';
        }
    }

    /**
     * Check if table exists.
     *
     * @param $table
     * @return false|true
     * @throws \Ilch\Database\Exception
     */
    public function existsTable($table)
    {
        return $this->db()->ifTableExists('[prefix]_'.$table);
    }
}
