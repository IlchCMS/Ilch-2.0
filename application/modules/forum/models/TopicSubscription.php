<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The topic subscription model class.
 *
 * @package ilch
 */
class TopicSubscription extends Model
{
    /**
     * The id of the item.
     *
     * @var int
     */
    protected int $id;

    /**
     * The topic id.
     *
     * @var int
     */
    protected int $topic_id;

    /**
     * The user id.
     *
     * @var int
     */
    protected int $user_id;

    /**
     * Date of last notification
     *
     * @var string
     */
    protected string $last_notification;

    /**
     * Username of the user
     *
     * @var string
     */
    protected string $username;

    /**
     * Email address of the user
     *
     * @var string
     */
    protected string $emailAddress;

    /**
     * Date of the user's last activity.
     *
     * May be null if the user has never been active.
     *
     * @var string|null
     */
    protected ?string $lastActivity;

    /**
     * Sets the id.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the topic id.
     *
     * @param int $topic_id
     * @return TopicSubscription
     */
    public function setTopicId(int $topic_id): TopicSubscription
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * Gets the topic id.
     *
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->topic_id;
    }

    /**
     * Sets the user id.
     *
     * @param int $user_id
     * @return TopicSubscription
     */
    public function setUserId(int $user_id): TopicSubscription
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Gets the user id.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Sets the date of the last notification.
     *
     * @param string $last_notification
     * @return TopicSubscription
     */
    public function setLastNotification(string $last_notification): TopicSubscription
    {
        $this->last_notification = $last_notification;
        return $this;
    }

    /**
     * Gets the date of the last notification.
     *
     * @return string
     */
    public function getLastNotification(): string
    {
        return $this->last_notification;
    }

    /**
     * Sets the user name.
     *
     * @param string $username
     * @return TopicSubscription
     */
    public function setUsername(string $username): TopicSubscription
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the user name.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the email address of the user.
     *
     * @param string $emailAddress
     * @return TopicSubscription
     */
    public function setEmailAddress(string $emailAddress): TopicSubscription
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * Gets the email address of the user.
     *
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * Sets the user's last activity.
     *
     * @param string|null $lastActivity
     * @return TopicSubscription
     */
    public function setLastActivity(?string $lastActivity): TopicSubscription
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    /**
     * Gets the user's last activity.
     *
     * @return string|null
     */
    public function getLastActivity(): ?string
    {
        return $this->lastActivity ?? null;
    }
}
