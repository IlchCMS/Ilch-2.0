<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 2.1.44
 */

namespace Modules\User\Models;

class NotificationPermission extends \Ilch\Model
{
    /**
     * The id of the permission.
     *
     * @var int
     */
    protected $id;

    /**
     * The user id for which the permission is for.
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
     * The type of the notification.
     *
     * @var string
     */
    protected $type;

    /**
     * The module is (not) allowed to issue notifications.
     *
     * @var int
     */
    protected $granted;

    /**
     * Get the ID of the notification permisson.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the ID of the notification permisson.
     *
     * @param int $id
     * @return NotificationPermission
     */
    public function setId(int $id): NotificationPermission
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return NotificationPermission
     */
    public function setUserId(int $userId): NotificationPermission
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
     * @return NotificationPermission
     */
    public function setModule(string $module): NotificationPermission
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Gets the type of the notification permission.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the notification permission.
     *
     * @param string $type
     * @return NotificationPermission
     */
    public function setType(string $type): NotificationPermission
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get if the module is (not) allowed to issue notifications.
     *
     * @return bool
     */
    public function getGranted(): bool
    {
        return (bool)$this->granted;
    }

    /**
     * Set if the module is (not) allowed to issue notifications.
     *
     * @param bool $granted
     * @return NotificationPermission
     */
    public function setGranted(bool $granted): NotificationPermission
    {
        $this->granted = $granted;
        return $this;
    }
}
