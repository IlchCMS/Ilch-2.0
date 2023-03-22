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
    protected $id = 0;

    /**
     * The position of th team.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * The Name of the Team.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The Image of the Team.
     *
     * @var string
     */
    protected $img = '';

    /**
     * The Leader of the Team.
     *
     * @var string
     */
    protected $leader = '';

    /**
     * The CoLeader of the Team.
     *
     * @var string
     */
    protected $coLeader = '';

    /**
     * The Group Id of the Team.
     *
     * @var int
     */
    protected $groupId = 0;

    /**
     * The Opt Show of the Team.
     *
     * @var bool
     */
    protected $optIn = false;

    /**
     * The value of notifyLeader of the Team.
     *
     * @var bool
     */
    protected $notifyLeader = false;

    /**
     * The Opt Show of the Team.
     *
     * @var bool
     */
    protected $optShow = false;
    
    /**
     * @param array $entries
     * @return $this
     * @since 1.22.0
     */
    public function setByArray(array $entries): Teams
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['position'])) {
            $this->setPosition($entries['position']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['img'])) {
            $this->setImg($entries['img']);
        }
        if (isset($entries['leader'])) {
            $this->setLeader($entries['leader']);
        }
        if (isset($entries['coLeader'])) {
            $this->setCoLeader($entries['coLeader']);
        }
        if (isset($entries['groupId'])) {
            $this->setGroupId($entries['groupId']);
        }
        if (isset($entries['optShow'])) {
            $this->setOptShow($entries['optShow']);
        }
        if (isset($entries['optIn'])) {
            $this->setOptIn($entries['optIn']);
        }
        if (isset($entries['notifyLeader'])) {
            $this->setNotifyLeader($entries['notifyLeader']);
        }

        return $this;
    }

    /**
     * Gets the Id of the team.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the Id of the Team.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Teams
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the position of the team.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position of the team.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Teams
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the Name of the Team.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Name of the Team.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Teams
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Image of the Team.
     *
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * Sets the Image of the Team.
     *
     * @param string $img
     * @return $this
     */
    public function setImg(string $img): Teams
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Gets the Leader of the Team.
     *
     * @return string
     */
    public function getLeader(): string
    {
        return $this->leader;
    }

    /**
     * Sets the Leader of the Team.
     *
     * @param string $leader
     * @return $this
     */
    public function setLeader(string $leader): Teams
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * Gets the CoLeader of the Team.
     *
     * @return string
     */
    public function getCoLeader(): string
    {
        return $this->coLeader;
    }

    /**
     * Sets the CoLeader of the Team.
     *
     * @param string $coLeader
     * @return $this
     */
    public function setCoLeader(string $coLeader): Teams
    {
        $this->coLeader = $coLeader;

        return $this;
    }

    /**
     * Gets the Group Id of the Team.
     *
     * @return int
     */
    public function getGroupId(): int
    {
        return $this->groupId;
    }

    /**
     * Sets the Group Id of the Team.
     *
     * @param int $groupId
     * @return $this
     */
    public function setGroupId(int $groupId): Teams
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Gets the Opt Show of the Team.
     *
     * @return bool
     */
    public function getOptShow(): bool
    {
        return $this->optShow;
    }

    /**
     * Sets the Opt Show of the Team.
     *
     * @param bool $optShow
     * @return $this
     */
    public function setOptShow(bool $optShow): Teams
    {
        $this->optShow = $optShow;

        return $this;
    }

    /**
     * Gets the Opt In of the Team.
     *
     * @return bool
     */
    public function getOptIn(): bool
    {
        return $this->optIn;
    }

    /**
     * Sets the Opt In of the Team.
     *
     * @param bool $optIn
     * @return $this
     */
    public function setOptIn(bool $optIn): Teams
    {
        $this->optIn = $optIn;

        return $this;
    }

    /**
     * Get value of notifyLeader
     *
     * @return bool
     */
    public function getNotifyLeader(): bool
    {
        return $this->notifyLeader;
    }

    /**
     * Set value of notifyLeader
     *
     * @param bool $notifyLeader
     * @return Teams
     */
    public function setNotifyLeader(bool $notifyLeader): Teams
    {
        $this->notifyLeader = $notifyLeader;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.22.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(($withId ? ['id' => $this->getId()] : []),
            [
                'name' => $this->getName(),
                'position' => $this->getPosition(),
                'img' => $this->getImg(),
                'leader' => $this->getLeader(),
                'coLeader' => $this->getCoLeader(),
                'groupId' => $this->getGroupId(),
                'optShow' => $this->getOptShow(),
                'optIn' => $this->getOptIn(),
                'notifyLeader' => $this->getNotifyLeader()
            ]);
    }
}
