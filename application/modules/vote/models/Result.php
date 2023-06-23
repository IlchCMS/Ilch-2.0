<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Models;

class Result extends \Ilch\Model
{
    /**
     * The pollId of the Vote.
     *
     * @var int
     */
    protected $pollId = 0;

    /**
     * The reply of the Vote.
     *
     * @var string
     */
    protected $reply = '';

    /**
     * The result of the Vote.
     *
     * @var int
     */
    protected $result = 0;

    /**
     * @param array $entries
     * @return $this
     * @since 1.12.0
     */
    public function setByArray(array $entries): Result
    {
        if (isset($entries['poll_id'])) {
            $this->setPollId($entries['poll_id']);
        }
        if (isset($entries['reply'])) {
            $this->setReply($entries['reply']);
        }
        if (isset($entries['result'])) {
            $this->setResult($entries['result']);
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
    public function setPollId(int $pollId): Result
    {
        $this->pollId = $pollId;

        return $this;
    }

    /**
     * Gets the reply of the Vote.
     *
     * @return string
     */
    public function getReply(): string
    {
        return $this->reply;
    }

    /**
     * Sets the reply of the Vote.
     *
     * @param string $reply
     *
     * @return $this
     */
    public function setReply(string $reply): Result
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Gets the result of the Vote.
     *
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * Sets the result of the Vote.
     *
     * @param int $result
     *
     * @return $this
     */
    public function setResult(int $result): Result
    {
        $this->result = $result;

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
            'reply' => $this->getReply(),
            'result' => $this->getResult(),
        ];
    }
}
