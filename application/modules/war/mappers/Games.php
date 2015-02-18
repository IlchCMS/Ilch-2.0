<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Games as GamesModel;

defined('ACCESS') or die('no direct access');

class Games extends \Ilch\Mapper
{
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
}
