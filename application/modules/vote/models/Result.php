<?php
/**
 * @copyright Ilch 2.0
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
    protected $pollId;

    /**
     * The reply of the Vote.
     *
     * @var string
     */
    protected $reply;

    /**
     * The result of the Vote.
     *
     * @var int
     */
    protected $result;

    /**
     * Gets the pollId of the Vote.
     *
     * @return int
     */
    public function getPollId()
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
    public function setPollId($pollId)
    {
        $this->pollId = (int)$pollId;

        return $this;
    }

    /**
     * Gets the reply of the Vote.
     *
     * @return string
     */
    public function getReply()
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
    public function setReply($reply)
    {
        $this->reply = (string)$reply;

        return $this;
    }

    /**
     * Gets the result of the Vote.
     *
     * @return int
     */
    public function getResult()
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
    public function setResult($result)
    {
        $this->result = (int)$result;

        return $this;
    }
}
