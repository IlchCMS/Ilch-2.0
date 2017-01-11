<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Models;

class Teams extends \Ilch\Model
{
    /**
     * The Id of the Team.
     *
     * @var int
     */
    protected $id;

    /**
     * The Name of the Team.
     *
     * @var string
     */
    protected $name;

    /**
     * The Image of the Team.
     *
     * @var string
     */
    protected $img;

    /**
     * The Leader of the Team.
     *
     * @var int
     */
    protected $leader;

    /**
     * The CoLeader of the Team.
     *
     * @var int
     */
    protected $coLeader;

    /**
     * The GroupId of the Team.
     *
     * @var int
     */
    protected $groupId;

    /**
     * Sets the Id of the Team.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the Name of the Team.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name of the Team.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the Image of the Team.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Sets the Image of the Team.
     *
     * @param string $img
     * @return this
     */
    public function setImg($img)
    {
        $this->img = (string)$img;

        return $this;
    }

    /**
     * Gets the Leader of the Team.
     *
     * @return int
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * Sets the Leader of the Team.
     *
     * @param int $leader
     * @return this
     */
    public function setLeader($leader)
    {
        $this->leader = (int)$leader;

        return $this;
    }

    /**
     * Gets the CoLeader of the Team.
     *
     * @return int
     */
    public function getCoLeader()
    {
        return $this->coLeader;
    }

    /**
     * Sets the CoLeader of the Team.
     *
     * @param int $coLeader
     * @return this
     */
    public function setCoLeader($coLeader)
    {
        $this->coLeader = (int)$coLeader;

        return $this;
    }

    /**
     * Gets the GroupId of the Team.
     *
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Sets the GroupId of the Team.
     *
     * @param int $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = (int)$groupId;
    }
}
