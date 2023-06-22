<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Models;

class Ip extends \Ilch\Model
{
    /**
     * The pollId of the Vote.
     *
     * @var int
     */
    protected $pollId = 0;

    /**
     * The ip of the Vote.
     *
     * @var string
     */
    protected $ip = '';

    /**
     * The user id of the Vote.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * @param array $entries
     * @return $this
     * @since 1.12.0
     */
    public function setByArray(array $entries): Ip
    {
        if (isset($entries['poll_id'])) {
            $this->setPollId($entries['poll_id']);
        }
        if (isset($entries['ip'])) {
            $this->setIP($entries['ip']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }

        return $this;
    }

    /**
     * Gets the pollId of the Vote.
     *
     * @return int
     */
    public function getPollId(): int
    {
        return $this->pollId;
    }

    /**
     * Sets the pollId of the Vote.
     *
     * @param int $pollId
     *
     * @return $this
     */
    public function setPollId(int $pollId): Ip
    {
        $this->pollId = $pollId;

        return $this;
    }

    /**
     * Gets the ip of the Vote.
     *
     * @return string
     */
    public function getIP(): string
    {
        return $this->ip;
    }

    /**
     * Sets the ip of the Vote.
     *
     * @param string $ip
     *
     * @return $this
     */
    public function setIP(string $ip): Ip
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Gets the user id of the Vote.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the Vote.
     *
     * @param int $userId
     *
     * @return $this
     */
    public function setUserId(int $userId): Ip
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return array
     * @since 1.12.0
     */
    public function getArray(): array
    {
        return [
            'poll_id' => $this->getPollId(),
            'ip' => $this->getIP(),
            'user_id' => $this->getUserId()
        ];
    }
}
