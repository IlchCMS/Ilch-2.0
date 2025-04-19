<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Ilch\Pagination;
use Modules\Downloads\Models\File as FileModel;

class File extends Mapper
{
    /**
     * Get the file by id.
     *
     * @param int $id
     * @return FileModel|null
     */
    public function getFileById(int $id): ?FileModel
    {
        $fileRow = $this->db()->select(['g.file_id', 'g.item_id', 'fileid' => 'g.id', 'g.visits', 'g.file_title', 'g.file_description', 'g.file_image', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'downloads_files'])
            ->join(['m' => 'media'], 'g.file_id = m.id', 'LEFT')
            ->where(['g.id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new FileModel();
        $entryModel->setId($id);
        $entryModel->setFileId($fileRow['file_id']);
        $entryModel->setFileUrl($fileRow['url'] ?? '');
        $entryModel->setFileImage($fileRow['file_image']);
        $entryModel->setFileTitle($fileRow['file_title']);
        $entryModel->setFileDesc($fileRow['file_description']);
        $entryModel->setItemId($fileRow['item_id']);
        $entryModel->setVisits($fileRow['visits']);

        return $entryModel;
    }

    /**
     * Get the last file by the item id.
     *
     * @param int $itemId
     * @return FileModel|null
     */
    public function getLastFileByItemId(int $itemId): ?FileModel
    {
        $fileRow = $this->db()->select(['g.file_id', 'g.item_id', 'fileid' => 'g.id', 'g.visits', 'g.file_title', 'g.file_description', 'g.file_image', 'm.url', 'm.id', 'm.url_thumb'])
            ->from(['g' => 'downloads_files'])
            ->join(['m' => 'media'], 'g.file_id = m.id', 'LEFT')
            ->where(['g.item_id' => $itemId])
            ->order(['g.id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new FileModel();
        $entryModel->setFileId($fileRow['file_id']);
        $entryModel->setFileUrl($fileRow['url'] ?? '');
        $entryModel->setFileTitle($fileRow['file_title']);
        $entryModel->setFileImage($fileRow['file_image']);
        $entryModel->setFileDesc($fileRow['file_description']);
        $entryModel->setVisits($fileRow['visits']);

        return $entryModel;
    }

    /**
     * Get the count of files by the item id.
     *
     * @param int $itemId id of the item
     * @return int count of files
     */
    public function getCountOfFilesByItemId(int $itemId): int
    {
        return (int)$this->db()->select('Count(*)')
            ->from(['downloads_files'])
            ->where(['item_id' => $itemId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get the files by the item id.
     *
     * @param int $itemId id of the item
     * @param Pagination|null $pagination
     * @return array
     */
    public function getFilesByItemId(int $itemId, ?Pagination $pagination = null): array
    {
        $sql = $this->db()->select(['g.item_id', 'fileid' => 'g.id', 'g.file_title', 'g.file_image', 'g.file_description', 'g.visits', 'm.url', 'm.url_thumb'])
            ->from(['g' => 'downloads_files'])
            ->join(['m' => 'media'], 'g.file_image = m.url', 'LEFT')
            ->where(['g.item_id' => $itemId])
            ->order(['g.id' => 'DESC']);

        if ($pagination !== null) {
            $sql->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $sql->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $sql->execute();
        }

        $filesArray = $result->fetchRows();
        $entries = [];

        foreach ($filesArray as $entry) {
            $fileModel = new FileModel();
            $fileModel->setFileUrl($entry['url'] ?? '');
            $fileModel->setFileThumb($entry['url_thumb'] ?? '');
            $fileModel->setId($entry['fileid']);
            $fileModel->setFileTitle($entry['file_title']);
            $fileModel->setFileImage($entry['url_thumb'] ?? '');
            $fileModel->setFileDesc($entry['file_description']);
            $fileModel->setVisits($entry['visits']);
            $fileModel->setItemId($entry['item_id']);
            $entries[] = $fileModel;
        }

        return $entries;
    }

    /**
     * Inserts or updates file entry.
     *
     * @param FileModel $model
     */
    public function save(FileModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('downloads_files')
                ->values(['file_id' => $model->getFileId(), 'item_id' => $model->getItemId(), 'file_title' => $model->getFileTitle()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('downloads_files')
                ->values(['file_id' => $model->getFileId(), 'item_id' => $model->getItemId(), 'file_title' => $model->getFileTitle()])
                ->execute();
        }
    }

    /**
     * Delete a file by it's id.
     *
     * @param int $id the id of the file
     * @return Result|int
     */
    public function deleteById(int $id)
    {
        return $this->db()->delete('downloads_files')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates visits.
     *
     * @param FileModel $model
     */
    public function saveVisits(FileModel $model)
    {
        if ($model->getVisits()) {
            $this->db()->update('downloads_files')
                ->values(['visits' => $model->getVisits()])
                ->where(['file_id' => $model->getFileId()])
                ->execute();
        }
    }

    /**
     * Updates File meta.
     *
     * @param FileModel $model
     */
    public function saveFileTreat(FileModel $model)
    {
        $this->db()->update('downloads_files')
            ->values(['file_title' => $model->getFileTitle(), 'file_image' => $model->getFileImage(), 'file_description' => $model->getFileDesc()])
            ->where(['id' => $model->getId()])
            ->execute();
    }
}
