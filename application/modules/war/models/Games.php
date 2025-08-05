<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class Games extends Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The warId.
     *
     * @var int
     */
    protected $warId = 0;

    /**
     * The Game map.
     *
     * @var int
     */
    protected $map = 0;

    /**
     * The Group Points.
     *
     * @var int
     */
    protected $groupPoints = 0;

    /**
     * The Enemy Points.
     *
     * @var int
     */
    protected $enemyPoints = 0;

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Games
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['war_id'])) {
            $this->setWarId($entries['war_id']);
        }
        if (isset($entries['map'])) {
            $this->setMap($entries['map']);
        }
        if (isset($entries['group_points'])) {
            $this->setGroupPoints($entries['group_points']);
        }
        if (isset($entries['enemy_points'])) {
            $this->setEnemyPoints($entries['enemy_points']);
        }

        return $this;
    }

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Games
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the warId.
     *
     * @return int
     */
    public function getWarId(): int
    {
        return $this->warId;
    }

    /**
     * Sets the warId.
     *
     * @param int $warId
     * @return $this
     */
    public function setWarId(int $warId): Games
    {
        $this->warId = $warId;

        return $this;
    }

    /**
     * Gets the map.
     *
     * @return int
     */
    public function getMap(): int
    {
        return $this->map;
    }

    /**
     * Sets the map.
     *
     * @param int $map
     * @return $this
     */
    public function setMap(int $map): Games
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Gets the groupPoints.
     *
     * @return int
     */
    public function getGroupPoints(): int
    {
        return $this->groupPoints;
    }

    /**
     * Sets the groupPoints.
     *
     * @param int $groupPoints
     * @return $this
     */
    public function setGroupPoints(int $groupPoints): Games
    {
        $this->groupPoints = $groupPoints;

        return $this;
    }

    /**
     * Gets the enemyPoints.
     *
     * @return int
     */
    public function getEnemyPoints(): int
    {
        return $this->enemyPoints;
    }

    /**
     * Sets the enemyPoints.
     *
     * @param int $enemyPoints
     * @return $this
     */
    public function setEnemyPoints(int $enemyPoints): Games
    {
        $this->enemyPoints = $enemyPoints;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'war_id' => $this->getWarId(),
                'map' => $this->getMap(),
                'group_points' => $this->getGroupPoints(),
                'enemy_points' => $this->getEnemyPoints(),
            ]
        );
    }
}
