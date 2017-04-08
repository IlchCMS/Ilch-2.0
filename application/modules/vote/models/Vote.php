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
     * The question of the Vote.
     *
     * @var string
     */
    protected $question;

    /**
     * The key of the Vote.
     *
     * @var string
     */
    protected $key;

    /**
     * The group of the Vote.
     *
     * @var int
     */
    protected $group;

    /**
     * The status of the Vote.
     *
     * @var int
     */
    protected $status;

    /**
     * Gets the id of the Vote.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the Vote.
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
     * Gets the question of the Vote.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question of the Vote.
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
     * Gets the key of the Vote.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key of the Vote.
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
     * Gets the group of the Vote.
     *
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Sets the group of the Vote.
     *
     * @param int $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = (int)$group;

        return $this;
    }

    /**
     * Gets the status of the Vote.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of the Vote.
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
