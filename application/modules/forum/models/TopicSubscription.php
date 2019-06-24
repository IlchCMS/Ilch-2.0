<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The topic subscription model class.
 *
 * @package ilch
 */
class TopicSubscription extends \Ilch\Model
{
    /**
     * The id of the item.
     *
     * @var integer
     */
    protected $id;

    /**
     * The topic id.
     *
     * @var integer
     */
    protected $topic_id;

    /**
     * The user id.
     *
     * @var integer
     */
    protected $user_id;

    /**
     * Date of last notification
     *
     * @var integer
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the topic id.
     *
     * @param int $topic_id
     * @return TopicSubscription
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * Gets the topic id.
     *
     * @return int
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * Sets the user id.
     *
     * @param int $user_id
     * @return TopicSubscription
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Gets the user id.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Sets the date of the last notification.
     *
     * @param int $last_notification
     * @return TopicSubscription
     */
    public function setLastNotification($last_notification)
    {
        $this->last_notification = $last_notification;
        return $this;
    }

    /**
     * Gets the date of the last notification.
     *
     * @return int
     */
    public function getLastNotification()
    {
        return $this->last_notification;
    }

    /**
     * Sets the user name.
     *
     * @param string $username
     * @return TopicSubscription
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the user name.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the email address of the user.
     *
     * @param string $emailAddress
     * @return TopicSubscription
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * Gets the email address of the user.
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $lastActivity
     * @return TopicSubscription
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }
}
