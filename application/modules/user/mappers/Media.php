<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Media as MediaModel;

class Media extends \Ilch\Mapper
{
    /**
     * Gets the media list by ending.
     *
     * @param int $userId
     * @param string|null $ending Space-separated list of endings (e.g. "jpg jpeg png"). Returns null when null or empty.
     * @param \Ilch\Pagination|null $pagination
     * @return MediaModel[]|null
     * @throws \Ilch\Database\Exception
     */
    public function getMediaListByEnding(int $userId, ?string $ending = null, ?\Ilch\Pagination $pagination = null): ?array
    {
        $endings = array_values(array_filter(explode(' ', trim((string) $ending, " \f\n\r\t\v\x00"))));

        if (empty($endings)) {
            return null;
        }

        $escapedEndings = implode(',', $this->db()->escapeArray($endings, true));
        $where = 'user_id = ' . $userId . ' AND ending IN (' . $escapedEndings . ')';

        if ($pagination !== null) {
            $sqlCountOfRows = 'SELECT COUNT(*)
                FROM `[prefix]_users_media`
                WHERE ' . $where;

            $pagination->setRows($this->db()->queryCell($sqlCountOfRows));
        }

        $sql = 'SELECT *
                FROM `[prefix]_users_media`
                WHERE ' . $where . '
                ORDER by id DESC';

        if ($pagination !== null) {
            $sql .= ' LIMIT ' . implode(',', $pagination->getLimit());
        }

        $mediaArray = $this->db()->queryArray($sql);

        if (empty($mediaArray)) {
            return null;
        }

        $media = [];

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Gets the media list scroll.
     *
     * @param int|null $lastId
     * @return MediaModel[]|null
     */
    public function getMediaListScroll(?int $lastId = null): ?array
    {
        $query = $this->db()->select('*')
            ->from('users_media');

        if ($lastId !== null) {
            $query->where(['id <' => $lastId]);
        }

        $mediaRows = $query
            ->order(['id' => 'DESC'])
            ->limit(40)
            ->execute()
            ->fetchRows();

        if (empty($mediaRows)) {
            return null;
        }

        $media = [];

        foreach ($mediaRows as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Inserts media and writes the new auto-increment id back to the model.
     *
     * @param MediaModel $model
     */
    public function save(MediaModel $model): void
    {
        $lastInsertId = $this->db()->insert('users_media')
            ->values([
                'user_id' => $model->getUserId(),
                'name' => $model->getName(),
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'ending' => $model->getEnding(),
                'datetime' => $model->getDatetime(),
            ])
            ->execute();

        $model->setId($lastInsertId);
    }

    /**
     * Delete/Unlink Media by id.
     *
     * @param int $id
     */
    public function delMediaById(int $id): void
    {
        $mediaRow = $this->db()->select('*')
            ->from('users_media')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if ($mediaRow === null) {
            return;
        }

        if (file_exists($mediaRow['url'])) {
            unlink($mediaRow['url']);
        }

        if (file_exists($mediaRow['url_thumb'])) {
            unlink($mediaRow['url_thumb']);
        }

        $this->db()->delete('users_media')
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete('users_gallery_imgs')
            ->where(['image_id' => $id])
            ->execute();
    }
}
