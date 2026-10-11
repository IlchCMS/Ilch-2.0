<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

use DateTime;

class Logs extends \Ilch\Model
{
    /**
     * The user id of the log.
     */
    protected ?int $userId = null;

    /**
     * The date of the log.
     * Mappers store the database value, i.e. a datetime string,
     * but a DateTime object may be passed as well.
     */
    protected DateTime|string|null $date = null;

    /**
     * The info of the log.
     */
    protected ?string $info = null;

    /**
     * Gets the user id of the log.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the log.
     *
     * @param int|float|string $userId
     */
    public function setUserId(int|float|string $userId): void
    {
        $this->userId = (int) $userId;
    }

    /**
     * Gets the date of the log.
     *
     * @return DateTime|string|null
     */
    public function getDate(): DateTime|string|null
    {
        return $this->date;
    }

    /**
     * Sets the date of the log.
     *
     * @param DateTime|string $date
     */
    public function setDate(DateTime|string $date): void
    {
        $this->date = $date;
    }

    /**
     * Gets the info of the log.
     *
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * Sets the info of the log.
     *
     * @param string $info
     */
    public function setInfo(string $info): void
    {
        $this->info = $info;
    }
}
