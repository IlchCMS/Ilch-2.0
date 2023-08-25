<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The group rank model class.
 *
 * @package ilch
 */
class GroupRank extends Model
{
    /**
     * The id of the item.
     *
     * @var int
     */
    protected $id;

    /**
     * The group id.
     *
     * @var int
     */
    protected $group_id;
    
    /**
     * The rank of the group.
     *
     * @var int
     */
    protected $rank;

    /**
     * Sets the id.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->group_id;
    }

    /**
     * @param int $group_id
     * @return GroupRank
     */
    public function setGroupId(int $group_id): GroupRank
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return GroupRank
     */
    public function setRank(int $rank): GroupRank
    {
        $this->rank = $rank;
        return $this;
    }
}
