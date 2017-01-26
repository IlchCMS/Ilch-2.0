<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Games as GamesModel;

class Games extends \Ilch\Mapper
{
    /**
     * Gets the Games.
     *
     * @param string $id
     * @return GameModel[]|array
     */
    public function getGamesByWarId($id = null)
    {
        $select = $this->db()->select('*')
            ->from('war_played')
            ->where(['war_id' => $id])
            ->order(['war_id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($select)) {
            return null;
        }

        $games = [];

        foreach ($select as $game) {
            $gameModel = new GamesModel();
            $gameModel->setId($game['id']);
            $gameModel->setWarId($game['war_id']);
            $gameModel->setMap(($game['map']));
            $gameModel->setGroupPoints(($game['group_points']));
            $gameModel->setEnemyPoints(($game['enemy_points']));
            $games[] = $gameModel;
        }

        return $games;
    }

    /**
     * Gets the Games.
     *
     * @param array[]
     * @return GameModel[]|array
     */
    public function getGamesByWhere($where = [])
    {
        $select = $this->db()->select('*')
            ->from('war_played')
            ->where($where)
            ->order(['war_id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($select)) {
            return null;
        }

        $games = [];

        foreach ($select as $game) {
            $gameModel = new GamesModel();
            $gameModel->setId($game['id']);
            $gameModel->setWarId($game['war_id']);
            $gameModel->setMap(($game['map']));
            $gameModel->setGroupPoints(($game['group_points']));
            $gameModel->setEnemyPoints(($game['enemy_points']));
            $games[] = $gameModel;
        }

        return $games;
    }

    /**
     * Inserts or updates Game entry.
     *
     * @param GameModel $model
     */
    public function save(GamesModel $model)
    {
        $fields = [
            'war_id' => $model->getWarId(),
            'map' => $model->getMap(),
            'group_points' => $model->getGroupPoints(),
            'enemy_points' => $model->getEnemyPoints()
        ];

        if ($model->getId()) {
            $this->db()->update('war_played')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('war_played')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete the game.
     *
     * @param  $id
     */
    public function deleteById($id)
    {
            return $this->db()->delete('war_played')
            ->where(['id' => $id])
            ->execute();
    }
}
