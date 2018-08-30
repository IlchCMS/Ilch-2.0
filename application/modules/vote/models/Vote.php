<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Vote\Models;

class Vote extends \Ilch\Model
{
    /**
     * The id of the Vote.
     *
     * @var int
     */
    protected $id;

    /**
     * The question of the vote.
     *
     * @var string
     */
    protected $question;

    /**
     * The key of the vote.
     *
     * @var string
     */
    protected $key;

    /**
     * The groups of the vote.
     *
     * @var string
     */
    protected $groups;

    /**
     * The read access of the vote.
     *
     * @var string
     */
    protected $readAccess;

    /**
     * The status of the vote.
     *
     * @var int
     */
    protected $status;

    /**
     * Gets the id of the vote.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the vote.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the question of the vote.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question of the vote.
     *
     * @param string $question
     *
     * @return $this
     */
    public function setQuestion($question)
    {
        $this->question = (string)$question;

        return $this;
    }

    /**
     * Gets the key of the vote.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key of the vote.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * Gets the groups of the vote.
     *
     * @return string
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Sets the groups of the vote.
     *
     * @param string $groups
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->groups = (string)$groups;

        return $this;
    }

    /**
     * Gets the read access of the event.
     *
     * @return int
     */
    public function getReadAccess()
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access of the event.
     *
     * @param string $readAccess
     *
     * @return $this
     */
    public function setReadAccess($readAccess)
    {
        $this->readAccess = (string)$readAccess;

        return $this;
    }

    /**
     * Gets the status of the vote.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of the vote.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }
}
