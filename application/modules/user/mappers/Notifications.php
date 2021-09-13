<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 2.1.44
 */

namespace Modules\User\Mappers;

use Modules\User\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\User\Models\Notification as NotificationModel;
use Modules\User\Models\NotificationPermission as NotificationPermissionModel;
use Ilch\Validation;

class Notifications extends \Ilch\Mapper
{
    /**
     * Gets a notification by id.
     *
     * @param int $id
     * @return NotificationModel|null
     */
    public function getNotificationById(int $id)
    {
        $result = $this->db()->select('*')
            ->from('users_notifications')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $notificationModel = new NotificationModel();
        $notificationModel->setId($result['id']);
        $notificationModel->setUserId($result['user_id']);
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
     * @param array $order
     * @return array
     */
    private function getNotificationsBy(array $where = [], array $order = []): array
    {
        $array = $this->db()->select('*')
            ->from('users_notifications')
            ->where($where)
            ->order($order)
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
        }

        $notifications = [];
        foreach ($array as $entries) {
            $notificationModel = new NotificationModel();
            $notificationModel->setId($entries['id']);
            $notificationModel->setUserId($entries['user_id']);
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
     * @param int $userId
     * @return NotificationModel[]|[]
     */
    public function getNotifications(int $userId): array
    {
        return $this->getNotificationsBy(['user_id' => $userId]);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getNotificationsSortedByDateType(int $userId): array
    {
        return $this->getNotificationsBy(['user_id' => $userId], ['type' => 'ASC', 'id' => 'DESC']);
    }

    /**
     * Get notifications by module.
     *
     * @param string $module
     * @param int $userId
     * @return NotificationModel[]|[]
     */
    public function getNotificationsByModule(string $module, int $userId): array
    {
        return $this->getNotificationsBy(['module' => $module, 'user_id' => $userId]);
    }

    /**
     * Get notifications by type.
     *
     * @param string $type
     * @param int $userId
     * @return array
     */
    public function getNotificationsByType(string $type, int $userId): array
    {
        return $this->getNotificationsBy(['type' => $type, 'user_id' => $userId]);
    }

    /**
     * Check if notification is valid.
     *
     * @param NotificationModel $notification
     * @return bool true|false
     */
    public function isValidNotification(NotificationModel $notification): bool
    {
        $fields = [
            'module' => $notification->getModule(),
            'message' => $notification->getMessage(),
            'url' => $notification->getURL(),
            'type' => $notification->getType()
        ];

        $validation = Validation::create($fields, [
            'module' => 'required',
            'message' => 'required',
            'url' => 'required|url',
            'type' => 'required'
        ]);

        return $validation->isValid();
    }

    /**
     * Add a notification.
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
            'user_id' => $notification->getUserId(),
            'module' => $notification->getModule(),
            'message' => $notification->getMessage(),
            'url' => $notification->getURL(),
            'type' => $notification->getType()
        ];

        $notificationPermissionMapper = new NotificationPermissionMapper();
        $permissions = $notificationPermissionMapper->getPermissionsOfModule($notification->getModule(), $notification->getUserId());

        if ($permissions === []) {
            $newPermission = new NotificationPermissionModel();
            $newPermission->setUserId($notification->getUserId());
            $newPermission->setModule($notification->getModule());
            $newPermission->setType($notification->getType());
            $newPermission->setGranted(1);
            $notificationPermissionMapper->addPermissionForModule($newPermission);
        }

        // Permission not granted can mean: 1) no permission for the entire module 2) no permission for that type of notification
        foreach ($permissions as $permission) {
            if (!$permission->getGranted() && ($permission->getType() === null || $permission->getType() === $notification->getType())) {
                return 0;
            }
        }

        $this->db()->insert('users_notifications')
            ->values($fields)
            ->execute();

        return $this->db()->getLastInsertId();
    }

    /**
     * Add multiple notifications.
     *
     * @param NotificationModel[] $notifications
     * @return int
     */
    public function addNotifications(array $notifications): int
    {
        $notificationPermissionMapper = new NotificationPermissionMapper();

        $valuesOfRows = [];
        $newPermssions = [];
        $permissions = $notificationPermissionMapper->getPermissionsNotGranted();

        foreach ($notifications as $notification) {
            if (!$this->isValidNotification($notification)) {
                throw new InvalidArgumentException('Invalid notification.');
            }

            foreach ($permissions as $permission) {
                if ($notification->getUserId() === $permission->getUserId() && $notification->getModule() === $permission->getModule() && ($permission->getType() === null || $notification->getType() === $permission->getType())) {
                    continue 2;
                }
            }

            $valuesOfRows[] = [$notification->getUserId(), $notification->getModule(), $notification->getMessage(), $notification->getUrl(), $notification->getType()];
            $permissionModel = new NotificationPermissionModel();
            $permissionModel->setUserId($notification->getUserId());
            $permissionModel->setModule($notification->getModule());
            $permissionModel->setType($notification->getType());

            if (!\in_array($permissionModel, $newPermssions, true)) {
                $newPermssions[] = $permissionModel;
            }
        }

        if ($valuesOfRows !== []) {
            $affectedRows = 0;
            $chunks = array_chunk($valuesOfRows, 25);

            foreach ($chunks as $chunk) {
                $affectedRows += $this->db()->insert('users_notifications')
                    ->columns(['user_id', 'module', 'message', 'url', 'type'])
                    ->values($chunk)
                    ->execute();
            }

            $notificationPermissionMapper->addPermissions($newPermssions);
            return $affectedRows;
        }

        return 0;
    }

    /**
     * Update a notification (module, message, url, type).
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

        return $this->db()->update()->table('users_notifications')
            ->values($fields)
            ->where(['id' => $notification->getId()])
            ->execute();
    }

    /**
     * Delete notifications.
     *
     * @param array $where
     * @return int
     */
    private function deleteNotificationsBy(array $where): int
    {
        return $this->db()->delete('users_notifications')
            ->where($where)
            ->execute();
    }

    /**
     * Delete a notification by id.
     *
     * @param int $id
     * @param int $userId
     * @return int
     */
    public function deleteNotificationById(int $id, int $userId): int
    {
        return $this->deleteNotificationsBy(['id' => $id, 'user_id' => $userId]);
    }

    /**
     * Delete notifications by module.
     *
     * @param string $module
     * @param int $userId
     * @return int
     */
    public function deleteNotificationsByModule(string $module, int $userId): int
    {
        return $this->deleteNotificationsBy(['module' => $module, 'user_id' => $userId]);
    }

    /**
     * Delete notifications by type.
     *
     * @param string $module
     * @param string $type
     * @param int $userId
     * @return int
     */
    public function deleteNotificationsByType(string $module, string $type, int $userId): int
    {
        return $this->deleteNotificationsBy(['module' => $module, 'type' => $type, 'user_id' => $userId]);
    }
}
