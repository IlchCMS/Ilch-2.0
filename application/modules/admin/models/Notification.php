<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Notification extends \Ilch\Model
{
    /**
     * The id of the notification.
     */
    protected ?int $id = null;

    /**
     * The timestamp of the notification.
     * Stored as given (int, e.g. from time(), or string, e.g. from the database).
     */
    protected int|string|null $timestamp = null;

    /**
     * The module for which the notification is for.
     */
    protected ?string $module = null;

    /**
     * The message of the notification.
     */
    protected ?string $message = null;

    /**
     * The url of the notification.
     */
    protected ?string $url = null;

    /**
     * The type of the notification. This can be used to mark a notification as
     * a specific one e.g. "adminModuleUpdatesAvailable"
     */
    protected ?string $type = null;

    /**
     * Get the id of the notification
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the id of the notification
     *
     * @param int|float|string $id
     */
    public function setId(int|float|string $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * Get the timestamp of the notification
     *
     * @return int|string|null
     */
    public function getTimestamp(): int|string|null
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp of the notification
     *
     * @param int|string $timestamp
     */
    public function setTimestamp(int|string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the module for which the notification is for.
     *
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * Sets the module for which the notification is for.
     *
     * @param string $module
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * Gets the message of the notification.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Sets the message of the notification.
     *
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Gets the url of the notification.
     *
     * @return string|null
     */
    public function getURL(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the url of the notification.
     *
     * @param string $url
     */
    public function setURL(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Gets the type of the notification.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the notification.
     *
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
