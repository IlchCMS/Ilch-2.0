<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Models;

class Entrants extends \Ilch\Model
{
    /**
     * The eventId of the event entrants.
     *
     * @var int
     */
    protected $eventId;

    /**
     * The userId of the event entrants.
     *
     * @var int
     */
    protected $userId;

    /**
     * The user status of the event entrants.
     *
     * @var int
     */
    protected $status;

    /**
     * Gets the eventid of the event entrants.
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Sets the eventid of the event entrants.
     *
     * @param int $eventId
     * @return this
     */
    public function setEventId($eventId)
    {
        $this->eventId = (int)$eventId;

        return $this;
    }

    /**
     * Gets the user of the event entrants.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the userid of the event entrants.
     *
     * @param integer $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the status from user of the event entrants.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status from user of the event entrants.
     *
     * @param integer $status
     * @return this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }
}
