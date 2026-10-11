<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;
use Ilch\Validation;

class Notifications extends \Ilch\Mapper
{
    /**
     * Gets a notification by id.
     *
     * @param int $id
     * @return NotificationModel|null
     */
    public function getNotificationById(int $id): ?NotificationModel
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
     * Gets the notifications by the specified condition.
     *
     * @param array $where
     * @return NotificationModel[]
     */
    public function getNotificationsBy(array $where = []): array
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
     * Gets all notifications.
     *
     * @return NotificationModel[]
     */
    public function getNotifications(): array
    {
        return $this->getNotificationsBy();
    }

    /**
     * Gets the notifications by module.
     *
     * @param string $module
     * @return NotificationModel[]
     */
    public function getNotificationsByModule(string $module): array
    {
        return $this->getNotificationsBy(['module' => $module]);
    }

    /**
     * Gets the notifications by type.
     *
     * @param string $type
     * @return NotificationModel[]
     */
    public function getNotificationsByType(string $type): array
    {
        return $this->getNotificationsBy(['type' => $type]);
    }

    /**
     * Checks whether the notification is valid.
     *
     * @param NotificationModel $notification
     * @return bool
     */
    public function isValidNotification(NotificationModel $notification): bool
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
     * Adds a notification.
     *
     * @param NotificationModel $notification
     * @return int
     */
    public function addNotification(NotificationModel $notification): int
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

        if ($permission === null) {
            $permission = new NotificationPermissionModel();
            $permission->setModule($notification->getModule());
            $permission->setGranted(1);
            $permission->setLimit(5);
            $notificationPermissionMapper->addPermissionForModule($permission);
        }

        // If granted is 0 then there is no permission for this module. limit = 0 means no limit.
        if ($permission->getGranted() && ($count < $permission->getLimit() || $permission->getLimit() == 0)) {
            $this->db()->insert('admin_notifications')
                ->values($fields)
                ->execute();

            return $this->db()->getLastInsertId();
        }

        return 0;
    }

    /**
     * Updates a notification (module, message, url, type).
     *
     * @param NotificationModel $notification
     * @return int
     */
    public function updateNotificationById(NotificationModel $notification): int
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

        return $this->db()->update()->table('admin_notifications')
            ->values($fields)
            ->where(['id' => $notification->getId()])
            ->execute();
    }

    /**
     * Deletes a notification by id.
     *
     * @param int $id
     */
    public function deleteNotificationById(int $id): void
    {
        $this->db()->delete('admin_notifications')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes the notifications by module.
     *
     * @param string $module
     */
    public function deleteNotificationsByModule(string $module): void
    {
        $this->db()->delete('admin_notifications')
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Deletes the notifications by type.
     *
     * @param string $type
     */
    public function deleteNotificationsByType(string $type): void
    {
        $this->db()->delete('admin_notifications')
            ->where(['type' => $type])
            ->execute();
    }

    /**
     * Deletes all notifications by truncating the table.
     */
    public function deleteAllNotifications(): void
    {
        $this->db()->truncate('[prefix]_admin_notifications');
    }
}
