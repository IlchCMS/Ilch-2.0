<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Mappers;

use Modules\Downloads\Models\File as FileModel;

class File extends \Ilch\Mapper
{
    public function getFileById($id)
    {
        $sql = 'SELECT g.file_id,g.cat,g.id as fileid,g.visits,g.file_title,g.file_description,g.file_image, m.url, m.id, m.url_thumb
                           FROM `[prefix]_downloads_files` AS g
                           LEFT JOIN `[prefix]_media` m ON g.file_id = m.id

                           WHERE g.id = '.(int)$id;
        $fileRow = $this->db()->queryRow($sql);

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new FileModel();
        $entryModel->setFileId($fileRow['file_id']);
        $entryModel->setFileUrl($fileRow['url']);
        $entryModel->setFileImage($fileRow['file_image']);
        $entryModel->setFileTitle($fileRow['file_title']);
        $entryModel->setFileDesc($fileRow['file_description']);
        $entryModel->setCat($fileRow['cat']);
        $entryModel->setVisits($fileRow['visits']);

        return $entryModel;
    }

    public function getLastFileByDownloadsId($id)
    {
        $sql = 'SELECT g.file_id,g.cat,g.id as fileid,g.visits,g.file_title,g.file_image,g.file_description, m.url, m.id, m.url_thumb
                           FROM `[prefix]_downloads_files` AS g
                           LEFT JOIN `[prefix]_media` m ON g.file_id = m.id

                           WHERE g.cat = '.(int)$id.' ORDER by g.id DESC LIMIT 1';
        $fileRow = $this->db()->queryRow($sql);

        if (empty($fileRow)) {
            return null;
        }

        $entryModel = new FileModel();
        $entryModel->setFileId($fileRow['file_id']);
        $entryModel->setFileUrl($fileRow['url']);
        $entryModel->setFileTitle($fileRow['file_title']);
        $entryModel->setFileImage($fileRow['file_image']);
        $entryModel->setFileDesc($fileRow['file_description']);
        $entryModel->setVisits($fileRow['visits']);

        return $entryModel;
    }

    public function getCountFileById($id)
    {
        $sql = 'SELECT *
                FROM `[prefix]_downloads_files`
                
                WHERE cat = '.(int)$id;
        return $this->db()->queryArray($sql);
    }

    /**
     * Inserts or updates File entry.
     *
     * @param FileModel $model
     */
    public function save(FileModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('downloads_files')
                ->values(['file_id' => $model->getFileId(),'cat' => $model->getCat(), 'file_title' => $model->getFileTitle()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('downloads_files')
                ->values(['file_id' => $model->getFileId(),'cat' => $model->getCat(), 'file_title' => $model->getFileTitle()])
                ->execute();
        }
    }

    public function getFileByDownloadsId($id, $pagination = NULL)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS g.file_id,g.cat,g.id as fileid,g.file_title,g.file_image,g.file_description,g.visits, m.url, m.id, m.url_thumb
                           FROM `[prefix]_downloads_files` AS g
                           LEFT JOIN `[prefix]_media` m ON g.file_image = m.url

                           WHERE g.cat = '.(int)$id.' ORDER BY g.id DESC
                           LIMIT '.implode(',',$pagination->getLimit());

        $fileArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        $entry = [];

        foreach ($fileArray as $entries) {
            $entryModel = new FileModel();
            $entryModel->setFileUrl($entries['url']);
            $entryModel->setFileThumb($entries['url_thumb']);
            $entryModel->setId($entries['fileid']);
            $entryModel->setFileTitle($entries['file_title']);
            $entryModel->setFileImage($entries['url_thumb']);
            $entryModel->setFileDesc($entries['file_description']);
            $entryModel->setVisits($entries['visits']);
            $entryModel->setCat($entries['cat']);
            $entry[] = $entryModel;
        }
        return $entry;
    }

    public function deleteById($id)
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
     * @param ImageModel $model
     */
    public function saveFileTreat(FileModel $model)
    {
        $this->db()->update('downloads_files')
                ->values(['file_title' => $model->getFileTitle(),'file_image' => $model->getFileImage(),'file_description' => $model->getFileDesc()])
                ->where(['id' => $model->getId()])
                ->execute();
    }
}
