<?php
/**
 * @copyright Ilch 2
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
     * The position of th team.
     *
     * @var int
     */
    protected $position;

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
     * The Group Id of the Team.
     *
     * @var int
     */
    protected $groupId;

    /**
     * The Opt Show of the Team.
     *
     * @var int
     */
    protected $optIn;

    /**
     * The value of notifyLeader of the Team.
     *
     * @var int
     */
    protected $notifyLeader;

    /**
     * The Opt Show of the Team.
     *
     * @var int
     */
    protected $optShow;

    /**
     * Sets the Id of the Team.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the position of the team.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position of the team.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;

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
     * @return $this
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
     * @return $this
     */
    public function setImg($img)
    {
        $this->img = (string)$img;

        return $this;
    }

    /**
     * Gets the Leader of the Team.
     *
     * @return string
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * Sets the Leader of the Team.
     *
     * @param string $leader
     * @return $this
     */
    public function setLeader($leader)
    {
        $this->leader = (string)$leader;

        return $this;
    }

    /**
     * Gets the CoLeader of the Team.
     *
     * @return string
     */
    public function getCoLeader()
    {
        return $this->coLeader;
    }

    /**
     * Sets the CoLeader of the Team.
     *
     * @param string $coLeader
     * @return $this
     */
    public function setCoLeader($coLeader)
    {
        $this->coLeader = (string)$coLeader;

        return $this;
    }

    /**
     * Gets the Group Id of the Team.
     *
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Sets the Group Id of the Team.
     *
     * @param int $groupId
     * @return $this
     */
    public function setGroupId($groupId)
    {
        $this->groupId = (int)$groupId;

        return $this;
    }

    /**
     * Gets the Opt Show of the Team.
     *
     * @return int
     */
    public function getOptShow()
    {
        return $this->optShow;
    }

    /**
     * Sets the Opt Show of the Team.
     *
     * @param int $optShow
     * @return $this
     */
    public function setOptShow($optShow)
    {
        $this->optShow = (int)$optShow;

        return $this;
    }

    /**
     * Gets the Opt In of the Team.
     *
     * @return int
     */
    public function getOptIn()
    {
        return $this->optIn;
    }

    /**
     * Sets the Opt In of the Team.
     *
     * @param int $optIn
     * @return $this
     */
    public function setOptIn($optIn)
    {
        $this->optIn = (int)$optIn;

        return $this;
    }

    /**
     * Get value of notifyLeader
     *
     * @return int
     */
    public function getNotifyLeader()
    {
        return $this->notifyLeader;
    }

    /**
     * Set value of notifyLeader
     *
     * @param int $notifyLeader
     * @return Teams
     */
    public function setNotifyLeader($notifyLeader)
    {
        $this->notifyLeader = $notifyLeader;
        return $this;
    }
}
