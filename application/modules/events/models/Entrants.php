<?php

/**
 * @copyright Ilch 2
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
    protected $eventId = 0;

    /**
     * The userId of the event entrants.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The user status of the event entrants.
     *
     * @var int
     */
    protected $status = 0;

    /**
     * Gets the eventid of the event entrants.
     *
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * Sets the eventid of the event entrants.
     *
     * @param int $eventId
     *
     * @return $this
     */
    public function setEventId(int $eventId): Entrants
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Gets the user of the event entrants.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the userid of the event entrants.
     *
     * @param int $userId
     *
     * @return $this
     */
    public function setUserId(int $userId): Entrants
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the status from user of the event entrants.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the status from user of the event entrants.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status): Entrants
    {
        $this->status = $status;

        return $this;
    }
}
