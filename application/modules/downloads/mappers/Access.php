<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Mappers;

use Ilch\Mapper;

/**
 * Mapper so handle the access rights for DownloadsItems and Files.
 *
 * @since 1.15.0
 */
class Access extends Mapper
{
    /**
     * @var string
     */
    public string $accessTable = 'downloads_access';

    /**
     * @var string
     */
    public string $accessFilesTable = 'downloads_files_access';

    /**
     * Save access rights for a DownloadsItem.
     *
     * @param int $itemId
     * @param string|null $access
     * @return void
     * @since 1.15.0
     */
    public function save(int $itemId, ?string $access)
    {
        $this->saveAccess($itemId, $access);
    }

    /**
     * Save access rights for a file.
     *
     * @param int $fileId
     * @param string|null $access
     * @return void
     * @since 1.15.0
     */
    public function saveFileAccess(int $fileId, ?string $access)
    {
        $this->saveAccess($fileId, $access, true);
    }

    /**
     * Save access rights for a file or a DownloadsItem.
     *
     * @param int $id
     * @param string|null $access
     * @param bool $isFileId
     * @return void
     * @since 1.15.0
     */
    private function saveAccess(int $id, ?string $access, bool $isFileId = false)
    {
        if ($access === null) {
            return;
        }

        $columnName = ($isFileId) ? 'file_id' : 'item_id';
        $tableName = ($isFileId) ? $this->accessFilesTable : $this->accessTable;

        $this->db()->delete($tableName)
            ->where([$columnName => $id])
            ->execute();

        $access = explode(',', $access);

        $preparedRows = [];
        foreach ($access as $groupId) {
            if ($groupId) {
                $preparedRows[] = [$id, $groupId];
            }
        }

        if (count($preparedRows)) {
            // Add access rights in chunks of 25 to the table.
            $chunks = array_chunk($preparedRows, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert($tableName)
                    ->columns([$columnName, 'group_id'])
                    ->values($chunk)
                    ->execute();
            }
        }
    }
}
