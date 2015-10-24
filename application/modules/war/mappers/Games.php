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
            ->where(array('war_id' => $id))
            ->order(array('war_id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($select)) {
            return null;
        }

        $games = array();

        foreach ($select as $game) {
            $gameModel = new GamesModel();
            $gameModel->setId($game['id']);
            $gameModel->setWarId($game['war_id']);
            $gameModel->setMap(($game['map']));
            $gameModel->setGroupPoints(($game['group_pionts']));
            $gameModel->setEnemyPoints(($game['enemy_pionts']));
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
        $fields = array
        (
            'war_id' => $model->getWarId(),
            'map' => $model->getMap(),
            'group_pionts' => $model->getGroupPoints(),
            'enemy_pionts' => $model->getEnemyPoints(),
        );

        $this->db()->insert('war_played')
                ->values($fields)
                ->execute();
        
    }

    /**
     * Delete the game.
     *
     * @param  $id
     */
    public function deleteById($id)
    {
            return $this->db()->delete('war_played')
            ->where(array('id' => $id))
            ->execute();
    }
}
