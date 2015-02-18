<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

defined('ACCESS') or die('no direct access');

class Games extends \Ilch\Model
{
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
     * Gets the id of the group.
     *
     * @return integer
     */
    public function getWarId()
    {
        return $this->warId;
    }

    /**
     * Sets the id of the group.
     *
     * @param integer $warId
     */
    public function setWarId($warId)
    {
        $this->warId = (int)$warId;
    }

    /**
     * Gets the war enemy.
     *
     * @return string
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Sets the war enemy.
     *
     * @param string $map
     */
    public function setMap($map)
    {
        $this->map = (string)$map;
    }

    /**
     * Gets the war group.
     *
     * @return string
     */
    public function getGroupPoints()
    {
        return $this->groupPoints;
    }

    /**
     * Sets the war group.
     *
     * @param string $groupPoints
     */
    public function setGroupPoints($groupPoints)
    {
        $this->groupPoints = (string)$groupPoints;
    }

    /**
     * Gets the war time.
     *
     * @return string
     */
    public function getEnemyPoints()
    {
        return $this->enemyPoints;
    }

    /**
     * Sets the war time.
     *
     * @param string $enemyPoints
     */
    public function setEnemyPoints($enemyPoints)
    {
        $this->enemyPoints = (string)$enemyPoints;
    }
}
