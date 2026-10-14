<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Models;

use Modules\User\Models\User;
use Modules\Vote\Mappers\Ip as IpMapper;

class Vote extends \Ilch\Model
{
    /**
     * The id of the Vote.
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * The question of the vote.
     *
     * @var string
     */
    protected string $question = '';

    /**
     * The key of the vote.
     *
     * @var string
     */
    protected string $key = '';

    /**
     * The groups of the vote.
     *
     * @var string
     */
    protected string $groups = '';

    /**
     * The read access of the vote.
     *
     * @var string
     */
    protected string $readAccess = '2,3';

    /**
     * The status of the vote.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * The multiple reply of the vote.
     *
     * @var bool
     */
    protected bool $multiple_reply = false;

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
        // A vote without any poll_access row produces a NULL read_access from
        // the LEFT JOIN/GROUP_CONCAT of the Vote mapper. Treat that as "no
        // read access" ('') instead of silently falling back to the default
        // value.
        if (array_key_exists('read_access', $entries)) {
            $this->setReadAccess($entries['read_access'] ?? '');
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
     * Check if user is allowed to vote.
     *
     * The vote is allowed if the client IP (and, if given, the user) has not
     * voted on this vote yet, the vote is not locked and either the vote's
     * groups value is 'all' or one of the given group ids matches one of the
     * vote's groups (loose comparison).
     *
     * @param string $clientIP
     * @param User|null $user
     * @param array $groupIds The group ids of the user, e.g. [3]
     * @return bool
     * @since 1.14.0
     */
    public function canVote(string $clientIP, ?User $user, $groupIds = []): bool
    {
        $userId = $user?->getId();

        $ipMapper = new IpMapper();

        $ip = $ipMapper->getIP($this->getId(), $clientIP);
        $votedUser = $ipMapper->getVotedUser($this->getId(), $userId);

        if ($ip !== null || $votedUser !== null || $this->getStatus()) {
            return false;
        }

        if ($this->getGroups() === 'all') {
            return true;
        }

        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }
        if (!\is_array($groupIds)) {
            $groupIds = [];
        }

        $pollGroups = explode(',', $this->getGroups());
        foreach ($pollGroups as $pollGroup) {
            if (in_array($pollGroup, $groupIds)) {
                return true;
            }
        }

        return false;
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
                'question'          => $this->getQuestion(),
                'key'               => $this->getKey(),
                'status'            => $this->getStatus(),
                'groups'            => $this->getGroups(),
                'read_access_all'   => ($this->getReadAccess() === 'all' ? 1 : 0),
                'multiple_reply'    => $this->getMultipleReply(),
            ]
        );
    }
}
