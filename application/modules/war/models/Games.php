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
     * @var string
     */
    protected $map = '';

    /**
     * The Group Points.
     *
     * @var string
     */
    protected $groupPoints = '';

    /**
     * The Enemy Points.
     *
     * @var string
     */
    protected $enemyPoints = '';

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
     * @return string
     */
    public function getMap(): string
    {
        return $this->map;
    }

    /**
     * Sets the map.
     *
     * @param string $map
     * @return $this
     */
    public function setMap(string $map): Games
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Gets the groupPoints.
     *
     * @return string
     */
    public function getGroupPoints(): string
    {
        return $this->groupPoints;
    }

    /**
     * Sets the groupPoints.
     *
     * @param string $groupPoints
     * @return $this
     */
    public function setGroupPoints(string $groupPoints): Games
    {
        $this->groupPoints = $groupPoints;

        return $this;
    }

    /**
     * Gets the enemyPoints.
     *
     * @return string
     */
    public function getEnemyPoints(): string
    {
        return $this->enemyPoints;
    }

    /**
     * Sets the enemyPoints.
     *
     * @param string $enemyPoints
     * @return $this
     */
    public function setEnemyPoints(string $enemyPoints): Games
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
