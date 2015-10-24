<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

class Games extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var integer
     */
    protected $id;

    /**
     * The warId.
     *
     * @var integer
     */
    protected $warId;

    /**
     * The Game map.
     *
     * @var string
     */
    protected $map;

    /**
     * The Group Points.
     *
     * @var string
     */
    protected $groupPoints;

    /**
     * The Enemy Points.
     *
     * @var string
     */
    protected $enemyPoints;

    /**
     * Gets the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the warId.
     *
     * @return integer
     */
    public function getWarId()
    {
        return $this->warId;
    }

    /**
     * Sets the warId.
     *
     * @param integer $warId
     */
    public function setWarId($warId)
    {
        $this->warId = (int)$warId;
    }

    /**
     * Gets the map.
     *
     * @return string
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Sets the map.
     *
     * @param string $map
     */
    public function setMap($map)
    {
        $this->map = (string)$map;
    }

    /**
     * Gets the groupPoints.
     *
     * @return string
     */
    public function getGroupPoints()
    {
        return $this->groupPoints;
    }

    /**
     * Sets the groupPoints.
     *
     * @param string $groupPoints
     */
    public function setGroupPoints($groupPoints)
    {
        $this->groupPoints = (string)$groupPoints;
    }

    /**
     * Gets the enemyPoints.
     *
     * @return string
     */
    public function getEnemyPoints()
    {
        return $this->enemyPoints;
    }

    /**
     * Sets the enemyPoints.
     *
     * @param string $enemyPoints
     */
    public function setEnemyPoints($enemyPoints)
    {
        $this->enemyPoints = (string)$enemyPoints;
    }
}
