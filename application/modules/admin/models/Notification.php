<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id of the notification
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get the timestamp of the notification
     *
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp of the notification
     *
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Gets the module for which the notification is for.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the module for which the notification is for.
     *
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Gets the message of the notification.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the message of the notification.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Gets the url of the notification.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Sets the url of the notification.
     *
     * @param string $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the type of the notification.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type of the notification.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
