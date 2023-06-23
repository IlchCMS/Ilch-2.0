<?php

/**
 * @copyright Ilch 2
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
    protected $id = 0;

    /**
     * The question of the vote.
     *
     * @var string
     */
    protected $question = '';

    /**
     * The key of the vote.
     *
     * @var string
     */
    protected $key = '';

    /**
     * The groups of the vote.
     *
     * @var string
     */
    protected $groups = '';

    /**
     * The read access of the vote.
     *
     * @var string
     */
    protected $readAccess = '2,3';

    /**
     * The status of the vote.
     *
     * @var bool
     */
    protected $status = false;

    /**
     * The read access all of the vote.
     *
     * @var int
     */
    protected $read_access_all = 0;

    /**
     * The multiple reply of the vote.
     *
     * @var int
     */
    protected $multiple_reply = 0;

    /**
     * @param array $entries
     * @return $this
     * @since 1.12.0
     */
    public function setByArray(array $entries): Vote
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['question'])) {
            $this->setQuestion($entries['question']);
        }
        if (isset($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (isset($entries['status'])) {
            $this->setStatus($entries['status']);
        }
        if (isset($entries['multiple_reply'])) {
            $this->setMultipleReply($entries['multiple_reply']);
        }
        if (isset($entries['groups'])) {
            $this->setGroups($entries['groups']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['read_access_all'])) {
            if ($entries['read_access_all']) {
                $this->setReadAccess('all');
            }
        }

        return $this;
    }

    /**
     * Gets the id of the vote.
     *
     * @return int
     */
    public function getId(): int
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
    public function setId(int $id): Vote
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the question of the vote.
     *
     * @return string
     */
    public function getQuestion(): string
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
    public function setQuestion(string $question): Vote
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Gets the key of the vote.
     *
     * @return string
     */
    public function getKey(): string
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
    public function setKey(string $key): Vote
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the groups of the vote.
     *
     * @return string
     */
    public function getGroups(): string
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
    public function setGroups(string $groups): Vote
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Gets the read access of the event.
     *
     * @return string
     */
    public function getReadAccess(): string
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
    public function setReadAccess(string $readAccess): Vote
    {
        $this->readAccess = $readAccess;

        return $this;
    }

    /**
     * Gets the status of the vote.
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Sets the status of the vote.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function setStatus(bool $status): Vote
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the multiple reply of the vote.
     *
     * @return bool
     * @since 1.12.0
     */
    public function getMultipleReply(): bool
    {
        return $this->multiple_reply;
    }

    /**
     * Sets the multiple reply of the vote.
     *
     * @param bool $multiple_reply
     *
     * @return $this
     * @since 1.12.0
     */
    public function setMultipleReply(bool $multiple_reply): Vote
    {
        $this->multiple_reply = $multiple_reply;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.12.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'question' => $this->getQuestion(),
                'key' => $this->getKey(),
                'status' => $this->getStatus(),
                'groups' => $this->getGroups(),
                'read_access_all'   => ($this->getReadAccess() === 'all' ? 1 : 0),
                'multiple_reply' => $this->getMultipleReply(),
            ]
        );
    }
}
