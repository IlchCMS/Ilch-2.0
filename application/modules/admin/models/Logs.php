<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Logs extends \Ilch\Model
{
    /**
     * The id of the log.
     *
     * @var int
     */
    protected $id;

    /**
     * The user id of the log.
     *
     * @var int
     */
    protected $userId;

    /**
     * The datetime of the log.
     *
     * @var DateTime
     */
    protected $date;

    /**
     * The info of the log.
     *
     * @var string
     */
    protected $info;

    /**
     * Gets the id of the log.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the log.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the user id of the log.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the log.
     *
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
    }

    /**
     * Gets the date timestamp of the log.
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date timestamp of the log.
     *
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Gets the info of the log.
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets the info of the log.
     *
     * @param string $info
     */
    public function setInfo($info)
    {
        $this->info = (string) $info;
    }
}
