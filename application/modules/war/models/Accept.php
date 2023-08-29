<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class Accept extends Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The warId
     *
     * @var int
     */
    protected $warId = 0;

    /**
     * The userId
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The accept
     *
     * @var int
     */
    protected $accept = 0;

    /**
     * The comment
     *
     * @var string
     */
    protected $comment = '';

    /**
     * The datetime when the Accept got created.
     *
     * @var string
     */
    protected $dateCreated;

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Accept
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['war_id'])) {
            $this->setWarId($entries['war_id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['accept'])) {
            $this->setAccept($entries['accept']);
        }
        if (isset($entries['comment'])) {
            $this->setComment($entries['comment']);
        }
        if (isset($entries['date_created'])) {
            $this->setDateCreated($entries['date_created']);
        }

        return $this;
    }

    /**
     * Gets the id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Accept
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the userId
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the userId
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): Accept
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the warId
     *
     * @return int
     */
    public function getWarId(): int
    {
        return $this->warId;
    }

    /**
     * Sets the warId
     *
     * @param int $warId
     * @return $this
     */
    public function setWarId(int $warId): Accept
    {
        $this->warId = $warId;

        return $this;
    }

    /**
     * Gets the accept.
     *
     * @return int
     */
    public function getAccept(): int
    {
        return $this->accept;
    }

    /**
     * Sets the accept.
     *
     * @param int $accept
     * @return $this
     */
    public function setAccept(int $accept): Accept
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Gets the comment.
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Sets the comment.
     *
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment): Accept
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Gets the date_created timestamp of the Accept.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the Accept.
     *
     * @param string $dateCreated
     * @return $this
     */
    public function setDateCreated(string $dateCreated): Accept
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'war_id' => $this->getWarId(),
                'user_id' => $this->getUserId(),
                'accept' => $this->getAccept(),
                'comment' => $this->getComment(),
                'date_created' => $this->getDateCreated(),
            ]
        );
    }
}
