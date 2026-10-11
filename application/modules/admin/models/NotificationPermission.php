<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class NotificationPermission extends \Ilch\Model
{
    /**
     * The module for which the notification is for.
     */
    protected ?string $module = null;

    /**
     * The module is (not) allowed to issue notifications.
     * Stored as int (1/0), as in the original implementation.
     */
    protected ?int $granted = null;

    /**
     * The number of messages the module is allowed to issue.
     */
    protected ?int $limit = null;

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
     * Get if the module is (not) allowed to issue notifications.
     *
     * @return int|null
     */
    public function getGranted(): ?int
    {
        return $this->granted;
    }

    /**
     * Set if the module is (not) allowed to issue notifications.
     *
     * @param bool|int|float|string $granted
     */
    public function setGranted(bool|int|float|string $granted): void
    {
        $this->granted = (int) $granted;
    }

    /**
     * Get the number of messages the module is allowed to issue.
     *
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Set the number of messages the module is allowed to issue.
     *
     * @param int|float|string $limit
     */
    public function setLimit(int|float|string $limit): void
    {
        $this->limit = (int) $limit;
    }
}
