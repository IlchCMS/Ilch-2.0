<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;

class NotificationPermission extends \Ilch\Mapper
{
    /**
     * Gets all notification permissions.
     *
     * @return NotificationPermissionModel[]
     */
    public function getPermissions(): array
    {
        $array = $this->db()->select('*')
            ->from('admin_notifications_permission')
            ->order(['module' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
        }

        $notificationPermissions = [];
        foreach ($array as $entries) {
            $notificationPermissionModel = new NotificationPermissionModel();
            $notificationPermissionModel->setModule($entries['module']);
            $notificationPermissionModel->setGranted($entries['granted']);
            $notificationPermissionModel->setLimit($entries['limit']);
            $notificationPermissions[] = $notificationPermissionModel;
        }

        return $notificationPermissions;
    }

    /**
     * Gets the notification permission of a module.
     *
     * @param string $module
     * @return NotificationPermissionModel|null
     */
    public function getPermissionOfModule(string $module): ?NotificationPermissionModel
    {
        $result = $this->db()->select('*')
            ->from('admin_notifications_permission')
            ->where(['module' => $module])
            ->execute()
            ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $notificationPermissionModel = new NotificationPermissionModel();
        $notificationPermissionModel->setModule($result['module']);
        $notificationPermissionModel->setGranted($result['granted']);
        $notificationPermissionModel->setLimit($result['limit']);

        return $notificationPermissionModel;
    }

    /**
     * Updates the notification permission of a module.
     *
     * @param NotificationPermissionModel $permission
     * @return int
     */
    public function updatePermissionOfModule(NotificationPermissionModel $permission): int
    {
        $fields = [
            'module' => $permission->getModule(),
            'granted' => $permission->getGranted(),
            'limit' => $permission->getLimit()
        ];

        return $this->db()->update()->table('admin_notifications_permission')
            ->values($fields)
            ->where(['module' => $permission->getModule()])
            ->execute();
    }

    /**
     * Updates the granted permission of a module.
     *
     * @param string $module
     * @param bool $granted
     * @return int
     */
    public function updatePermissionGrantedOfModule(string $module, bool $granted): int
    {
        return $this->db()->update()->table('admin_notifications_permission')
            ->values(['granted' => $granted])
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Updates the message limit of a module.
     *
     * @param string $module
     * @param int $limit
     * @return int
     */
    public function updateLimitOfModule(string $module, int $limit): int
    {
        return $this->db()->update()->table('admin_notifications_permission')
            ->values(['limit' => $limit])
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Adds a notification permission for a module.
     *
     * @param NotificationPermissionModel $permission
     */
    public function addPermissionForModule(NotificationPermissionModel $permission): void
    {
        $fields = [
            'module' => $permission->getModule(),
            'granted' => $permission->getGranted(),
            'limit' => $permission->getLimit()
        ];

        $count = $this->db()->select('COUNT(*)', 'admin_notifications_permission', ['module' => $permission->getModule()])
            ->execute()
            ->fetchCell();

        if ($count == 0) {
            $this->db()->insert('admin_notifications_permission')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes the notification permission of a module.
     *
     * @param string $module
     */
    public function deletePermissionOfModule(string $module): void
    {
        $this->db()->delete('admin_notifications_permission')
            ->where(['module' => $module])
            ->execute();
    }
}
