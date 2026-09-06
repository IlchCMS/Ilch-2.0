<?php

/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvteam\Models;

class Team extends \Ilch\Model
{
    /**
     * The Id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The Title.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The User Ids.
     *
     * @var string
     */
    protected $userIds = '';

    /**
     * The Position.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Gets the Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the Id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Team
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the Title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the Title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Team
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the User Ids.
     *
     * @return string
     */
    public function getUserIds(): string
    {
        return $this->userIds;
    }

    /**
     * Sets the User Ids.
     *
     * @param string $userIds
     */
    public function setUserIds(string $userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * Gets the Position.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Team
    {
        $this->position = $position;

        return $this;
    }
}
