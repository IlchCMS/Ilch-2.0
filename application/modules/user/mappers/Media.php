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
     * Gets the Media Lists by ending.
     *
     * @param string $ending
     * @param \Ilch\Pagination|null $pagination
     * @return MediaModel[]|array
     */
    public function getMediaListByEnding($userId, $ending = NULL, $pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_users_media`
                WHERE user_id = '.$userId.' AND ending IN ('
                    .implode(',',  $this->db()->escapeArray(explode(' ', $ending), true))
                    .')
                ORDER by id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $mediaArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

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
     * Gets the Media List Scroll.
     *
     * @param int $lastId
     * @return MediaModel[]|array
     */
    public function getMediaListScroll($lastId = NULL) 
    {
        $mediaRows = $this->db()->select('*')
            ->from('users_media')
            ->where(['id <' => (int)$lastId])
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
     * Inserts Media
     *
     * @param MediaModel $model
     */
    public function save(MediaModel $model)
    {
        $this->db()->insert('users_media')
            ->values([
                'user_id' => $model->getUserId(),
                'name' => $model->getName(),
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'ending' => $model->getEnding(),
                'datetime' => $model->getDatetime(),
            ])
            ->execute();
    }

    /**
     * Delete/Unlink Media by id.
     *
     * @param int $id
     */
    public function delMediaById($id) 
    {
        $mediaRow = $this->db()->select('*')
            ->from('users_media')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

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
