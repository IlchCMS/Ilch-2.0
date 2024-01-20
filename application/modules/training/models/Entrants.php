<?php

/**
 * @copyright Ilch 2
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
    protected $trainId = 0;
    /**
     * The userId of the training entrants.
     *
     * @var int
     */
    protected $userId = 0;
    /**
     * The note of the training entrants.
     *
     * @var string
     */
    protected $note = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Entrants
    {
        if (isset($entries['train_id'])) {
            $this->setTrainId($entries['train_id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['note'])) {
            $this->setNote($entries['note']);
        }
        return $this;
    }

    /**
     * Gets the trainId of the training entrants.
     *
     * @return int
     */
    public function getTrainId(): int
    {
        return $this->trainId;
    }

    /**
     * Sets the trainId of the training entrants.
     *
     * @param int $trainId
     * @return $this
     */
    public function setTrainId(int $trainId): Entrants
    {
        $this->trainId = $trainId;
        return $this;
    }

    /**
     * Gets the user of the training entrants.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the userid of the training entrants.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): Entrants
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets the note of the training entrants.
     *
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * Sets the note of the training entrants.
     *
     * @param string $note
     * @return $this
     */
    public function setNote(string $note): Entrants
    {
        $this->note = $note;
        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'train_id' => $this->getTrainId(),
            'user_id' => $this->getUserId(),
            'note' => $this->getNote()
            ];
    }
}
