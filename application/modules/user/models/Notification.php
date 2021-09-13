<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 2.1.44
 */

namespace Modules\User\Models;

class Notification extends \Ilch\Model
{
    /**
     * The id of the notification.
     *
     * @var int
     */
    protected $id;

    /**
     * The timestamp of the notification.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The user id or the recipient associated with the notification.
     *
     * @var int
     */
    protected $userId;

    /**
     * The module for which the notification is for.
     *
     * @var string
     */
    protected $module;

    /**
     * The message of the notification.
     *
     * @var string
     */
    protected $message;

    /**
     * The url of the notification.
     *
     * @var string
     */
    protected $url;

    /**
     * The type of the notification. This can be used to mark a notification as
     * a specific one e.g. "adminModuleUpdatesAvailable"
     *
     * @var string
     */
    protected $type;

    /**
     * Get the id of the notification
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the id of the notification
     *
     * @param int $id
     * @return Notification
     */
    public function setId(int $id): Notification
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the timestamp of the notification
     *
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp of the notification
     *
     * @param string $timestamp
     * @return Notification
     */
    public function setTimestamp(string $timestamp): Notification
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get the user id or the recipient associated with the notification.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the user id or the recipient associated with the notification.
     *
     * @param int $userId
     * @return Notification
     */
    public function setUserId(int $userId): Notification
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets the module for which the notification is for.
     *
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * Sets the module for which the notification is for.
     *
     * @param string $module
     * @return Notification
     */
    public function setModule(string $module): Notification
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Gets the message of the notification.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the message of the notification.
     *
     * @param string $message
     * @return Notification
     */
    public function setMessage(string $message): Notification
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Gets the url of the notification.
     *
     * @return string
     */
    public function getURL(): string
    {
        return $this->url;
    }

    /**
     * Sets the url of the notification.
     *
     * @param string $url
     * @return Notification
     */
    public function setURL(string $url): Notification
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Gets the type of the notification.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the notification.
     *
     * @param string $type
     * @return Notification
     */
    public function setType(string $type): Notification
    {
        $this->type = $type;
        return $this;
    }
}
