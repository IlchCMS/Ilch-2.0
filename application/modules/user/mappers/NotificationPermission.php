<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 2.1.44
 */

namespace Modules\User\Mappers;

use Modules\User\Models\NotificationPermission as NotificationPermissionModel;

class NotificationPermission extends \Ilch\Mapper
{
    /**
     * Get permission by id.
     *
     * @param int $id
     * @return NotificationPermissionModel|null
     */
    public function getPermissionById(int $id)
    {
        $row = $this->db()->select('*')
            ->from('users_notifications_permission')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($row)) {
            return null;
        }

        $notificationPermissionModel = new NotificationPermissionModel();
        $notificationPermissionModel->setId($row['id']);
        $notificationPermissionModel->setUserId($row['user_id']);
        $notificationPermissionModel->setModule($row['module']);
        $notificationPermissionModel->setType($row['type']);
        $notificationPermissionModel->setGranted($row['granted']);

        return $notificationPermissionModel;
    }

    /**
     * Get permissions.
     *
     * @param array $where
     * @return NotificationPermissionModel[]|array
     */
    private function getPermissionsBy(array $where = []): array
    {
        $array = $this->db()->select('*')
            ->from('users_notifications_permission')
            ->where($where)
            ->order(['module' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
        }

        $notificationPermissions = [];
        foreach ($array as $entries) {
            $notificationPermissionModel = new NotificationPermissionModel();
            $notificationPermissionModel->setId($entries['id']);
            $notificationPermissionModel->setUserId($entries['user_id']);
            $notificationPermissionModel->setModule($entries['module']);
            $notificationPermissionModel->setType($entries['type']);
            $notificationPermissionModel->setGranted($entries['granted']);
            $notificationPermissions[] = $notificationPermissionModel;
        }

        return $notificationPermissions;
    }

    /**
     * Get all notification permissions.
     *
     * @return NotificationPermissionModel[]|array
     */
    public function getPermissions(): array
    {
        return $this->getPermissionsBy();
    }

    /**
     * Get permissions not granted.
     *
     * @return NotificationPermissionModel[]
     */
    public function getPermissionsNotGranted(): array
    {
        return $this->getPermissionsBy(['granted' => false]);
    }

    /**
     * Get permissions of module.
     *
     * @param string $module
     * @param int $userId
     * @return NotificationPermissionModel[]
     */
    public function getPermissionsOfModule(string $module, int $userId): array
    {
        return $this->getPermissionsBy(['module' => $module, 'user_id' => $userId]);
    }

    /**
     * Update permission granted.
     *
     * @param array $where
     * @param bool $granted
     * @return int
     */
    private function updatePermissionGranted(array $where, bool $granted): int
    {
        return $this->db()->update()->table('users_notifications_permission')
            ->values(['granted' => $granted])
            ->where($where)
            ->execute();
    }

    /**
     * Update permission granted of module.
     *
     * @param string $module
     * @param int $userId
     * @param bool $granted
     * @return int
     */
    public function updatePermissionGrantedOfModule(string $module, int $userId, bool $granted): int
    {
        return $this->updatePermissionGranted(['module' => $module, 'type' => '', 'user_id' => $userId], $granted);
    }

    /**
     * Update permission granted of module for this specific type.
     *
     * @param string $module
     * @param string $type
     * @param int $userId
     * @param bool $granted
     * @return int
     */
    public function updatePermissionGrantedOfModuleType(string $module, string $type, int $userId, bool $granted): int
    {
        return $this->updatePermissionGranted(['module' => $module, 'type' => $type, 'user_id' => $userId], $granted);
    }

    /**
     * Update permission granted by id.
     *
     * @param array $ids
     * @param int $userId
     * @param bool $granted
     * @return int
     */
    public function updatePermissionGrantedById(array $ids, int $userId, bool $granted): int
    {
        return $this->updatePermissionGranted(['id' => $ids, 'user_id' => $userId], $granted);
    }

    /**
     * Add notification permission for module.
     *
     * @param NotificationPermissionModel $permissionModel
     * @return int|void
     */
    public function addPermissionForModule(NotificationPermissionModel $permissionModel)
    {
        $fields = [
            'user_id' => $permissionModel->getUserId(),
            'module' => $permissionModel->getModule(),
            'type' => $permissionModel->getType(),
            'granted' => $permissionModel->getGranted()
        ];

        $permissions = $this->getPermissionsOfModule($permissionModel->getModule(), $permissionModel->getUserId());

        foreach ($permissions as $permission) {
            // Permission not granted can mean: 1) no permission for the entire module 2) no permission for that type of notification
            if (!$permission->getGranted() && ($permission->getType() === null || $permission->getType() === $permissionModel->getType())) {
                return;
            }

            // Permission already exists. Nothing to add.
            if ($permission->getGranted() && ($permission->getType() === $permissionModel->getType())) {
                return;
            }
        }

        return $this->db()->insert('users_notifications_permission')
            ->values($fields)
            ->execute();
    }

    /**
     * Add multiple permissions.
     *
     * @param NotificationPermissionModel[] $permissionModels
     * @return int
     */
    public function addPermissions(array $permissionModels): int
    {
        $permissions = $this->getPermissions();
        $valuesOfRows = [];

        foreach ($permissions as $permission) {
            foreach ($permissionModels as $permissionModel) {
                if ($permission->getUserId() === $permissionModel->getUserId() && $permission->getModule() === $permissionModel->getModule() && ($permission->getType() === '' || $permission->getType() === $permissionModel->getType())) {
                    break;
                }

                $row = [$permissionModel->getUserId(), $permissionModel->getModule(), $permissionModel->getType()];
                if (!\in_array($row, $valuesOfRows, true)) {
                    $valuesOfRows[] = $row;
                }
            }
        }

        if ($valuesOfRows !== []) {
            $affectedRows = 0;
            $chunks = array_chunk($valuesOfRows, 25);

            foreach ($chunks as $chunk) {
                $affectedRows += $this->db()->insert('users_notifications_permission')
                    ->columns(['user_id', 'module', 'type'])
                    ->values($chunk)
                    ->execute();
            }

            return $affectedRows;
        }

        return 0;
    }

    /**
     * Delete notification permission.
     *
     * @param array $where
     * @return int affected rows.
     */
    private function deletePermissionBy(array $where): int
    {
        return $this->db()->delete('users_notifications_permission')
            ->where($where)
            ->execute();
    }

    /**
     * Delete notification permission of module for user.
     *
     * @param string $module
     * @param int $userId
     * @return int
     */
    public function deletePermissionOfModule(string $module, int $userId): int
    {
        return $this->deletePermissionBy(['module' => $module, 'user_id' => $userId]);
    }

    /**
     * Delete notification permission for this type of notificiation for this module and user.
     *
     * @param string $module
     * @param string $type
     * @param int $userId
     * @return int
     */
    public function deletePermissionForType(string $module, string $type, int $userId): int
    {
        return $this->deletePermissionBy(['module' => $module, 'type' => $type, 'user_id' => $userId]);
    }

    /**
     * Delete notification permissions by their IDs.
     *
     * @param int[] $ids Array of integers
     * @param int $userId
     * @return int
     */
    public function deletePermissionsById(array $ids, int $userId): int
    {
        return $this->deletePermissionBy(['id' => $ids, 'user_id' => $userId]);
    }

    /**
     * Delete other permissions of the module from the user.
     *
     * @param int $id
     * @return int
     */
    public function deleteOtherPermissionsOfModule(int $id): int
    {
        $permisson = $this->getPermissionById($id);

        if ($permisson) {
            return $this->deletePermissionBy(['module' => $permisson->getModule(), 'user_id' => $permisson->getUserId(), 'id <>' => $id]);
        }

        return 0;
    }
}
