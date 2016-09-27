<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Backup as BackupModel;

class Backup extends \Ilch\Mapper
{
    /**
     * Gets all backups.
     */
    public function getBackups()
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
            $backupModel->setFile($entries['file']);
            $backupModel->setDate($entries['date']);
            $backups[] = $backupModel;
        }

        return $backups;
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
            'file' => $backup->getFile(),
            'date' => $backup->getDate()
        ];

        $this->db()->insert('backup')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes backup with the given id.
     *
     * @param string $id
     */
    public function delete($id)
    {
        $fileRow = $this->db()->select('*')
            ->from('backup')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($fileRow['file'])) {
            unlink($fileRow['file']);
        }

        $this->db()->delete('backup')
            ->where(['id' => $id])
            ->execute();
    }
}
