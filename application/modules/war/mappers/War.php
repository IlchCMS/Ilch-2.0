<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\War as WarModel;

defined('ACCESS') or die('no direct access');

class War extends \Ilch\Mapper
{
    /**
     * Gets the Wars.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return WarModel[]|array
     */
    public function getWars($where = array(), $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war')
            ->where($where)
            ->order(array('time' => 'ASC'));

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['id']);
            $entryModel->setWarEnemy($entries['enemy']);
            $entryModel->setWarGroup($entries['group']);
            $entryModel->setWarTime($entries['time']);
            $entryModel->setWarMaps($entries['maps']);
            $entryModel->setWarServer($entries['server']);
            $entryModel->setWarPassword($entries['password']);
            $entryModel->setWarXonx($entries['xonx']);
            $entryModel->setWarGame($entries['game']);
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entryModel->setWarReport($entries['report']);
            $entryModel->setWarStatus($entries['status']);
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
    public function getNextWars($where = array(), $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war')
            ->where($where)
            ->order(array('id' => 'DESC'));

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['id']);
            $entryModel->setWarEnemy($entries['enemy']);
            $entryModel->setWarGroup($entries['group']);
            $entryModel->setWarTime($entries['time']);
            $entryModel->setWarMaps($entries['maps']);
            $entryModel->setWarServer($entries['server']);
            $entryModel->setWarPassword($entries['password']);
            $entryModel->setWarXonx($entries['xonx']);
            $entryModel->setWarGame($entries['game']);
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entryModel->setWarReport($entries['report']);
            $entryModel->setWarStatus($entries['status']);
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
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($warRow)) {
            return null;
        }

        $warModel = new WarModel();
        $warModel->setId($warRow['id']);
        $warModel->setWarEnemy($warRow['enemy']);
        $warModel->setWarGroup($warRow['group']);
        $warModel->setWarTime($warRow['time']);
        $warModel->setWarMaps($warRow['maps']);
        $warModel->setWarServer($warRow['server']);
        $warModel->setWarPassword($warRow['password']);
        $warModel->setWarXonx($warRow['xonx']);
        $warModel->setWarGame($warRow['game']);
        $warModel->setWarMatchtype($warRow['matchtype']);
        $warModel->setWarReport($warRow['report']);
        $warModel->setWarStatus($warRow['status']);

        return $warModel;
    }

    /**
     * Inserts or updates war entry.
     *
     * @param WarModel $model
     */
    public function save(WarModel $model)
    {
        $fields = array
        (
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
        );

        if ($model->getId()) {
            $this->db()->update('war')
                ->values($fields)
                ->where(array('id' => $model->getId()))
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
            ->where(array('id' => $id))
            ->execute();
    }

    public function getWarListByStatus($status = NULL, $pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,g.name as group_name,g.id as group_is,e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                WHERE status = "'.$status.'"
                ORDER by w.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $warArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id']);
            $entryModel->setWarEnemy($entries['enemy_name']);
            $entryModel->setWarGroup($entries['group_name']);
            $entryModel->setWarTime($entries['time']);
            $entryModel->setWarMaps($entries['maps']);
            $entryModel->setWarServer($entries['server']);
            $entryModel->setWarPassword($entries['password']);
            $entryModel->setWarXonx($entries['xonx']);
            $entryModel->setWarGame($entries['game']);
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entryModel->setWarReport($entries['report']);
            $entryModel->setWarStatus($entries['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarList($pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,g.name as group_name,g.id as group_is,e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                ORDER by w.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $warArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id']);
            $entryModel->setWarEnemy($entries['enemy_name']);
            $entryModel->setWarGroup($entries['group_name']);
            $entryModel->setWarTime($entries['time']);
            $entryModel->setWarMaps($entries['maps']);
            $entryModel->setWarServer($entries['server']);
            $entryModel->setWarPassword($entries['password']);
            $entryModel->setWarXonx($entries['xonx']);
            $entryModel->setWarGame($entries['game']);
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entryModel->setWarReport($entries['report']);
            $entryModel->setWarStatus($entries['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarListByStatusAndLimt($status = NULL, $limit = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS w.id as war_id,w.enemy,w.group,w.time,w.maps,w.server,w.password,w.xonx,w.game,w.matchtype,w.report,w.status,g.name as group_name,g.id as group_is,e.name as enemy_name,e.id as enemy_id
                FROM `[prefix]_war` as w
                LEFT JOIN [prefix]_war_groups as g ON w.group = g.id
                LEFT JOIN [prefix]_war_enemy as e ON w.enemy = e.id
                WHERE status = "'.$status.'"
                ORDER by w.id DESC
                LIMIT '.$limit;

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setId($entries['war_id']);
            $entryModel->setWarEnemy($entries['enemy_name']);
            $entryModel->setWarGroup($entries['group_name']);
            $entryModel->setWarTime($entries['time']);
            $entryModel->setWarMaps($entries['maps']);
            $entryModel->setWarServer($entries['server']);
            $entryModel->setWarPassword($entries['password']);
            $entryModel->setWarXonx($entries['xonx']);
            $entryModel->setWarGame($entries['game']);
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entryModel->setWarReport($entries['report']);
            $entryModel->setWarStatus($entries['status']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getWarOptDistinctXonx() 
    {
        $sql = 'SELECT DISTINCT xonx
                FROM `[prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

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
                FROM `[prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

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
                FROM `[prefix]_war';

        $warArray = $this->db()->queryArray($sql);

        if (empty($warArray)) {
            return null;
        }

        $entry = array();

        foreach ($warArray as $entries) {
            $entryModel = new WarModel();
            $entryModel->setWarMatchtype($entries['matchtype']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
}
