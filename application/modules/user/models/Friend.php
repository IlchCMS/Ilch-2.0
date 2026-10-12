<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Friend extends \Ilch\Model
{
    /**
     * Id of the friend.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * User Id.
     *
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * User Id of the friend.
     *
     * @var int|null
     */
    protected ?int $friendUserId = null;

    /**
     * Name of the friend.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * Avatar of the friend.
     *
     * @var string|null
     */
    protected ?string $avatar = null;

    /**
     * Friend request still open or already accepted.
     *
     * @var int|null
     */
    protected ?int $approved = null;

    /**
     * Sets the friend id.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the friend id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the user id.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the user id.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the friend.
     *
     * @param int $friendUserId
     * @return $this
     */
    public function setFriendUserId(int $friendUserId): static
    {
        $this->friendUserId = $friendUserId;

        return $this;
    }

    /**
     * Gets the user id of the friend.
     *
     * @return int|null
     */
    public function getFriendUserId(): ?int
    {
        return $this->friendUserId;
    }

    /**
     * Get name of the friend.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name of the friend.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get avatar of friend.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set avatar of friend.
     *
     * @param mixed $avatar
     * @return $this
     */
    public function setAvatar(mixed $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getApproved(): ?int
    {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     * @return $this
     */
    public function setApproved(mixed $approved): static
    {
        $this->approved = $approved;

        return $this;
    }
}
