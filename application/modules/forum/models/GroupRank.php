<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The group rank model class.
 *
 * @package ilch
 */
class GroupRank extends \Ilch\Model
{
    /**
     * The id of the item.
     *
     * @var integer
     */
    protected $id;

    /**
     * The group id.
     *
     * @var integer
     */
    protected $group_id;
    
    /**
     * The rank of the group.
     *
     * @var integer
     */
    protected $rank;

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @return string
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @param integer $group_id
     * @return GroupRank
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return GroupRank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }
}
