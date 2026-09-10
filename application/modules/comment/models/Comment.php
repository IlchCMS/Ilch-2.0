<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Comment\Models;

class Comment extends \Ilch\Model
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * @var int
     */
    protected int $fkId = 0;

    /**
     * @var string
     */
    protected string $key = '';

    /**
     * @var string
     */
    protected string $text = '';

    /**
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * @var string|null
     */
    protected ?string $dateCreated = null;

    /**
     * @var int
     */
    protected int $up = 0;

    /**
     * @var int
     */
    protected int $down = 0;

    /**
     * @var string|null
     */
    protected ?string $voted = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Comment
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getFKId(): int
    {
        return $this->fkId;
    }

    /**
     * @param int $fkId
     *
     * @return $this
     */
    public function setFKId(int $fkId): Comment
    {
        $this->fkId = $fkId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): Comment
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text): Comment
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return $this
     */
    public function setUserId(int $userId): Comment
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    /**
     * @param string $dateCreated
     *
     * @return $this
     */
    public function setDateCreated(string $dateCreated): Comment
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return int
     */
    public function getUp(): int
    {
        return $this->up;
    }

    /**
     * @param int $up
     *
     * @return $this
     */
    public function setUp(int $up): Comment
    {
        $this->up = $up;

        return $this;
    }

    /**
     * @return int
     */
    public function getDown(): int
    {
        return $this->down;
    }

    /**
     * @param int $down
     *
     * @return $this
     */
    public function setDown(int $down): Comment
    {
        $this->down = $down;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVoted(): ?string
    {
        return $this->voted;
    }

    /**
     * @param string $voted
     *
     * @return $this
     */
    public function setVoted(string $voted): Comment
    {
        $this->voted = $voted;

        return $this;
    }
}
