<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class NotificationPermission extends \Ilch\Model
{
    /**
     * The module for which the notification is for.
     *
     * @var string
     */
    protected $module;

    /**
     * The module is (not) allowed to issue notifications.
     *
     * @var int
     */
    protected $granted;

    /**
     * The number of messages the module is allowed to issue.
     *
     * @var int
     */
    protected $limit;

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
     * Get if the module is (not) allowed to issue notifications.
     *
     * @return bool
     */
    public function getGranted()
    {
        return $this->granted;
    }

    /**
     * Set if the module is (not) allowed to issue notifications.
     *
     * @param bool $granted
     */
    public function setGranted($granted)
    {
        $this->granted = (int) $granted;
    }

    /**
     * Get the number of messages the module is allowed to issue.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the number of messages the module is allowed to issue.
     *
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
    }
}
