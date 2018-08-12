<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Mappers\Notificationpermission as NotificationPermissionMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Modules\Admin\Models\Notificationpermission as NotificationPermissionModel;
use Ilch\Validation;

class Notifications extends \Ilch\Mapper
{
    /**
     * Gets a notification by id.
     *
     * @param int $id
     * @return NotificationModel|null
     */
    public function getNotificationById($id)
    {
        $result = $this->db()->select('*')
                ->from('admin_notifications')
                ->where(['id' => $id])
                ->execute()
                ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $notificationModel = new NotificationModel();
        $notificationModel->setId($result['id']);
        $notificationModel->setTimestamp($result['timestamp']);
        $notificationModel->setModule($result['module']);
        $notificationModel->setMessage($result['message']);
        $notificationModel->setURL($result['url']);
        $notificationModel->setType($result['type']);

        return $notificationModel;
    }

    /**
     * Get notifications by specified condition
     *
     * @param array $where
     * @return array
     */
    public function getNotificationsBy($where = [])
    {
        $array = $this->db()->select('*')
            ->from('admin_notifications')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
        }

        $notifications = [];
        foreach ($array as $entries) {
            $notificationModel = new NotificationModel();
            $notificationModel->setId($entries['id']);
            $notificationModel->setTimestamp($entries['timestamp']);
            $notificationModel->setModule($entries['module']);
            $notificationModel->setMessage($entries['message']);
            $notificationModel->setURL($entries['url']);
            $notificationModel->setType($entries['type']);
            $notifications[] = $notificationModel;
        }

        return $notifications;
    }

    /**
     * Get all notifications.
     *
     * @return NotificationModel[]|[]
     */
    public function getNotifications()
    {
        return $this->getNotificationsBy();
    }

    /**
     * Get notifications by module.
     *
     * @param string $module
     * @return NotificationModel[]|[]
     */
    public function getNotificationsByModule($module)
    {
        return $this->getNotificationsBy(['module' => $module]);
    }

    /**
     * Get notifications by type.
     *
     * @param $type
     * @return array
     */
    public function getNotificationsByType($type)
    {
        return $this->getNotificationsBy(['type' => $type]);
    }

    /**
     * Check if notification is valid.
     *
     * @param NotificationModel $notification
     * @return bool true|false
     */
    public function isValidNotification(NotificationModel $notification)
    {
        $fields = [
            'module' => $notification->getModule(),
            'message' => $notification->getMessage(),
            'url' => $notification->getURL()
        ];

        $validation = Validation::create($fields, [
            'module' => 'required',
            'message' => 'required',
            'url' => 'required|url'
        ]);

        return $validation->isValid();
    }

    /**
     * Add a notification.
     *
     * @param NotificationModel $notification
     * @return int
     */
    public function addNotification(NotificationModel $notification)
    {
        if (!$this->isValidNotification($notification)) {
            return 0;
        }

        $fields = [
            'module' => $notification->getModule(),
            'message' => $notification->getMessage(),
            'url' => $notification->getURL(),
            'type' => $notification->getType()
        ];

        $count = $this->db()->select()->fields('COUNT(*)')
            ->from('admin_notifications')
            ->where(['module' => $notification->getModule()])
            ->execute()
            ->fetchCell();

        $notificationPermissionMapper = new NotificationPermissionMapper();
        $permission = $notificationPermissionMapper->getPermissionOfModule($notification->getModule());

        if (empty($permission)) {
            $permission = new NotificationPermissionModel();
            $permission->setModule($notification->getModule());
            $permission->setGranted(1);
            $permission->setLimit(5);
            $notificationPermissionMapper->addPermissionForModule($permission);
        }

        // If granted is 0 then there is no permission for this module. limit = 0 means no limit.
        if ($permission->getGranted() And ($count < $permission->getLimit() or $permission->getLimit() == 0)) {
            $this->db()->insert('admin_notifications')
                ->values($fields)
                ->execute();

            return $this->db()->getLastInsertId();
        }

        return 0;
    }

    /**
     * Update a notification (module, message, url, type).
     *
     * @param NotificationModel $notification
     * @return int
     */
    public function updateNotificationById(NotificationModel $notification)
    {
        if (!$this->isValidNotification($notification)) {
            return 0;
        }

        $fields = [
            'module' => $notification->getModule(),
            'message' => $notification->getMessage(),
            'url' => $notification->getURL(),
            'type' => $notification->getType()
        ];

        $updated = $this->db()->update()->table('admin_notifications')
            ->values($fields)
            ->where(['id' => $notification->getId()])
            ->execute();

        return $updated;
    }

    /**
     * Delete a notification by id.
     *
     * @param int $id
     */
    public function deleteNotificationById($id)
    {
        $this->db()->delete('admin_notifications')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete notifications by module.
     *
     * @param string $module
     */
    public function deleteNotificationsByModule($module)
    {
        $this->db()->delete('admin_notifications')
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Delete notifications by type.
     *
     * @param string $type
     */
    public function deleteNotificationsByType($type)
    {
        $this->db()->delete('admin_notifications')
            ->where(['type' => $type])
            ->execute();
    }

    /**
     * Delete all notifications by truncating the table.
     *
     */
    public function deleteAllNotifications()
    {
        $this->db()->truncate('[prefix]_admin_notifications');
    }
}
