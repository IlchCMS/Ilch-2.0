<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\NotificationPermission as NotificationPermissionModel;

class NotificationPermission extends \Ilch\Mapper
{
    /**
     * Get all notification permissions.
     *
     * @return NotificationPermissionModel[]|[]
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
     * Get notification permission of module.
     *
     * @param string $module
     * @return NotificationPermissionModel|null
     */
    public function getPermissionOfModule($module)
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
     * Update notification permission of module.
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
     * Update permission granted of module.
     *
     * @param string $module
     * @param bool $granted
     * @return int
     */
    public function updatePermissionGrantedOfModule($module, $granted): int
    {
        return $this->db()->update()->table('admin_notifications_permission')
            ->values(['granted' => $granted])
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Update message limit of module.
     *
     * @param string $module
     * @param int $limit
     * @return int
     */
    public function updateLimitOfModule($module, $limit): int
    {
        return $this->db()->update()->table('admin_notifications_permission')
            ->values(['limit' => $limit])
            ->where(['module' => $module])
            ->execute();
    }

    /**
     * Add notification permission for module.
     *
     * @param NotificationPermissionModel $permission
     */
    public function addPermissionForModule(NotificationPermissionModel $permission)
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
     * Delete notification permission of module.
     *
     * @param string $module
     */
    public function deletePermissionOfModule($module)
    {
        $this->db()->delete('admin_notifications_permission')
            ->where(['module' => $module])
            ->execute();
    }
}
