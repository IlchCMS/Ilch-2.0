<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Models;

class Comment extends \Ilch\Model
{
    /**
     * @var integer
     */
    protected $id;
	
    /**
     * @var integer
     */
    protected $fkId;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var integer
     */
    protected $userId;
	
    /**
     * @var \DateTime
     */
    protected $dateCreated;
	
    /**
     * @var integer
     */
    protected $up;
	
    /**
     * @var integer
     */
    protected $down;

    /**
     * @var string
     */
    protected $voted;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = (int)$id;

        return $this;
    }
	
    /**
     * @return integer
     */
    public function getFKId(): int
    {
        return $this->fkId;
    }

    /**
     * @param integer $fkId
     *
     * @return $this
     */
    public function setFKId($fkId): self
    {
        $this->fkId = (int)$fkId;

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
    public function setKey($key): self
    {
        $this->key = (string)$key;

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
    public function setText($text): self
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param integer $userId
     *
     * @return $this
     */
    public function setUserId($userId): self
    {
        $this->userId = (int)$userId;

        return $this;
    }
	
    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     *
     * @return $this
     */
    public function setDateCreated($dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUp(): int
    {
        return $this->up;
    }

    /**
     * @param integer $up
     *
     * @return $this
     */
    public function setUp($up): self
    {
        $this->up = (int)$up;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDown(): int
    {
        return $this->down;
    }

    /**
     * @param integer $down
     *
     * @return $this
     */
    public function setDown($down): self
    {
        $this->down = (int)$down;

        return $this;
    }

    /**
     * @return integer
     */
    public function getVoted()
    {
        return $this->voted;
    }

    /**
     * @param integer $voted
     *
     * @return $this
     */
    public function setVoted($voted): self
    {
        $this->voted = (string)$voted;

        return $this;
    }
}
