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
    protected $id;

    /**
     * The topic id.
     *
     * @var int
     */
    protected $topic_id;

    /**
     * The user id.
     *
     * @var int
     */
    protected $user_id;

    /**
     * Date of last notification
     *
     * @var string
     */
    protected $last_notification;

    /**
     * Username of the user
     *
     * @var string
     */
    protected $username;

    /**
     * Email address of the user
     *
     * @var string
     */
    protected $emailAddress;

    /**
     * Email address of the user
     *
     * @var string
     */
    protected $lastActivity;

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
     * @param string $lastActivity
     * @return TopicSubscription
     */
    public function setLastActivity(string $lastActivity): TopicSubscription
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastActivity(): string
    {
        return $this->lastActivity;
    }
}
