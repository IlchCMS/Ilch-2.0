<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Backup as BackupModel;

class Backup extends \Ilch\Mapper
{
    /**
     * Gets all backups.
     */
    public function getBackups(): ?array
    {
        $array = $this->db()->select('*')
                ->from('backup')
                ->order(['id' => 'DESC'])
                ->execute()
                ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $backups = [];
        foreach ($array as $entries) {
            $backupModel = new BackupModel();
            $backupModel->setId($entries['id']);
            $backupModel->setName($entries['name']);
            $backupModel->setDate($entries['date']);
            $backups[] = $backupModel;
        }

        return $backups;
    }

    /**
     * Gets a backup by id.
     *
     * @param int $id
     * @return BackupModel|null
     */
    public function getBackupById(int $id): ?BackupModel
    {
        $result = $this->db()->select('*')
                ->from('backup')
                ->where(['id' => $id])
                ->execute()
                ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $backupModel = new BackupModel();
        $backupModel->setId($result['id']);
        $backupModel->setName($result['name']);
        $backupModel->setDate($result['date']);
        
        return $backupModel;
    }

    /**
     * Get the newest backup.
     *
     * @return BackupModel|null
     * @since 2.1.51
     */
    public function getLastBackup(): ?BackupModel
    {
        $result = $this->db()->select('*')
            ->from('backup')
            ->limit(1)
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $backupModel = new BackupModel();
        $backupModel->setId($result['id']);
        $backupModel->setName($result['name']);
        $backupModel->setDate($result['date']);

        return $backupModel;
    }

    /**
     * Inserts backup model.
     *
     * @param BackupModel $backup
     */
    public function save(BackupModel $backup)
    {
        $fields = [
            'name' => $backup->getName(),
            'date' => $backup->getDate()
        ];

        $this->db()->insert('backup')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes backup with the given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('backup')
            ->where(['id' => $id])
            ->execute();
    }
}
