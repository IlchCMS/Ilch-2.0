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
    protected int $id = 0;

    /**
     * The Title.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * The User Ids.
     *
     * @var string
     */
    protected string $userIds = '';

    /**
     * The Position.
     *
     * @var int
     */
    protected int $position = 0;

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
     * @return Team
     */
    public function setUserIds(string $userIds): Team
    {
        $this->userIds = $userIds;

        return $this;
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
