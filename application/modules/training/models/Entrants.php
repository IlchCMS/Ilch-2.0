<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Models;

class Entrants extends \Ilch\Model
{
    /**
     * The trainingId of the training entrants.
     *
     * @var int
     */
    protected $trainId;

    /**
     * The userId of the training entrants.
     *
     * @var int
     */
    protected $userId;

    /**
     * The note of the training entrants.
     *
     * @var string
     */
    protected $note;

    /**
     * Gets the trainId of the training entrants.
     *
     * @return int
     */
    public function getTrainId()
    {
        return $this->trainId;
    }

    /**
     * Sets the trainId of the training entrants.
     *
     * @param int $trainId
     * @return this
     */
    public function setTrainId($trainId)
    {
        $this->trainId = (int)$trainId;

        return $this;
    }

    /**
     * Gets the user of the training entrants.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the userid of the training entrants.
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
     * Gets the note of the training entrants.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Sets the note of the training entrants.
     *
     * @param string $note
     * @return this
     */
    public function setNote($note)
    {
        $this->note = (string)$note;

        return $this;
    }
}
