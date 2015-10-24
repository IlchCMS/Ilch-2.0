<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Models;

class Away extends \Ilch\Model
{
    /**
     * The id of the away.
     *
     * @var int
     */
    protected $id;

    /**
     * The userId of the away.
     *
     * @var int
     */
    protected $userId;

    /**
     * The reason of the away.
     *
     * @var string
     */
    protected $reason;

    /**
     * The start of the away.
     *
     * @var string
     */
    protected $start;

    /**
     * The end of the away.
     *
     * @var string
     */
    protected $end;

    /**
     * The text of the away.
     *
     * @var string
     */
    protected $text;

    /**
     * The status of the away.
     *
     * @var int
     */
    protected $status;

    /**
     * Gets the id of the away.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the away.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the userId of the away.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the userId of the away.
     *
     * @param int $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the reason of the away.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets the reason of the away.
     *
     * @param string $reason
     * @return this
     */
    public function setReason($reason)
    {
        $this->reason = (string)$reason;

        return $this;
    }

    /**
     * Gets the start of the away.
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start of the away.
     *
     * @param string $start
     * @return this
     */
    public function setStart($start)
    {
        $this->start = (string)$start;

        return $this;
    }

    /**
     * Gets the end of the away.
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end of the away.
     *
     * @param string $end
     * @return this
     */
    public function setEnd($end)
    {
        $this->end = (string)$end;

        return $this;
    }

    /**
     * Gets the text of the away.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the away.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the status of the away.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of the away.
     *
     * @param int $status
     * @return this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }
}
