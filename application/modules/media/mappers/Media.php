<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Mappers;

use Modules\Media\Models\Media as MediaModel;

class Media extends \Ilch\Mapper
{
    /**
     * Gets the Media List.
     *
     * @param \Ilch\Pagination|null $pagination
     * @return MediaModel[]|array
     */
    public function getMediaList($pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS m.id,m.url,m.url_thumb,m.name,m.datetime,m.ending,m.cat,c.cat_name
                FROM `[prefix]_media` as m
                LEFT JOIN [prefix]_media_cats as c ON m.cat = c.id
                ORDER by m.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $mediaArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $entryModel->setCatName(($medias['cat_name']));
            $entryModel->setCatId(($medias['cat']));
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Gets the Media List.
     *
     * @return MediaModel[]|array
     */
    public function getMediaListAll()
    {
        $sql = 'SELECT m.id,m.url,m.url_thumb,m.name,m.datetime,m.ending,m.cat,c.cat_name
                FROM `[prefix]_media` as m
                LEFT JOIN [prefix]_media_cats as c ON m.cat = c.id
                ORDER by m.id DESC';

        $mediaArray = $this->db()->queryArray($sql);

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $entryModel->setCatName(($medias['cat_name']));
            $entryModel->setCatId(($medias['cat']));
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Gets the Media Lists by ending.
     *
     * @param string $ending
     * @param \Ilch\Pagination|null $pagination
     * @return MediaModel[]|array
     */
    public function getMediaListByEnding($ending = NULL, $pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS m.id,m.url,m.url_thumb,m.name,m.datetime,m.ending,m.cat,c.cat_name
                FROM `[prefix]_media` as m
                LEFT JOIN [prefix]_media_cats as c ON m.cat = c.id
                WHERE m.ending IN ("'.implode(',',array(str_replace(' ', '","', $ending))).'")
                ORDER by m.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $mediaArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $entryModel->setCatName(($medias['cat_name']));
            $entryModel->setCatId(($medias['cat']));
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
        $sql = 'SELECT m.id,m.url,m.url_thumb,m.name,m.datetime,m.ending,m.cat,c.cat_name
                FROM `[prefix]_media` as m
                LEFT JOIN [prefix]_media_cats as c ON m.cat = c.id
                WHERE m.id < '.$lastId.'
                ORDER by m.id DESC
                LIMIT 40';

        $mediaArray = $this->db()->query($sql);

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setUrl($medias['url']);
            $entryModel->setUrlThumb($medias['url_thumb']);
            $entryModel->setName($medias['name']);
            $entryModel->setDatetime($medias['datetime']);
            $entryModel->setEnding($medias['ending']);
            $entryModel->setCatName(($medias['cat_name']));
            $entryModel->setCatId(($medias['cat']));
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Gets the Cats.
     *
     * @return MediaModel[]|array
     */
    public function getCatList() 
    {
        $mediaArray = $this->db()->select('*')
            ->from('media_cats')
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($mediaArray)) {
            return null;
        }

        $media = array();

        foreach ($mediaArray as $medias) {
            $entryModel = new MediaModel();
            $entryModel->setId($medias['id']);
            $entryModel->setCatName(($medias['cat_name']));
            $media[] = $entryModel;
        }

        return $media;
    }

    /**
     * Get cat by id
     *
     * @param int $id
     * @return MediaModel
     */
    public function getCatById($id)
    {
        $catRow = $this->db()->select('*')
            ->from('media_cats')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($catRow)) {
            return null;
        }

        $mediaModel = new MediaModel();
        $mediaModel->setId($catRow['id']);
        $mediaModel->setCatName($catRow['cat_name']);

        return $mediaModel;
    }

    public function getByWhere($where = [])
    {
        $mediaRow = $this->db()->select('*')
            ->from('media')
            ->where($where)
            ->execute()
            ->fetchAssoc();

        if (empty($mediaRow)) {
            return null;
        }

        $mediaModel = new MediaModel();
        $mediaModel->setUrlThumb($mediaRow['url_thumb']);
        $mediaModel->setUrl($mediaRow['url']);
        
        return $mediaModel;
    }

    /**
     * Inserts Media
     *
     * @param MediaModel $model
     */
    public function save(MediaModel $model)
    {
        $this->db()->insert('media')
            ->values(array(
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'name' => $model->getName(),
                'datetime' => $model->getDatetime(),
                'ending' => $model->getEnding(),
                'cat' => '0',
                'cat_name' => 'Allgemein',
            ))
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
            ->from('media')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();
        if (file_exists($mediaRow['url'])) {
            unlink($mediaRow['url']);
        }
        if (file_exists($mediaRow['url_thumb'])) {
            unlink($mediaRow['url_thumb']);
        }
        $this->db()->delete('media')
            ->where(array('id' => $id))
            ->execute();
    }

    /**
     * Inserts Cat
     *
     * @param MediaModel $model
     */
    public function saveCat(MediaModel $model)
    {
        $this->db()->insert('media_cats')
            ->values(array(
                'cat_name' => $model->getCatName()
            ))
            ->execute();
    }

    /**
     * Delete Cat by id.
     *
     * @param int $id
     */
    public function delCatById($id) 
    {
        $this->db()->delete('media_cats')
            ->where(array('id' => $id))
            ->execute();
    }

    /**
     * Set Cat on Media
     *
     * @param MediaModel $model
     */
    public function setCat(MediaModel $model) 
    {
        $this->db()->update('media')
            ->values(array(
                'cat' => $model->getCatId()
            ))
            ->where(array('id' => $model->getId()))
            ->execute();
    }

    /**
     * Update Cat
     *
     * @param MediaModel $model
     */
    public function treatCat(MediaModel $model) 
    {
        $this->db()->update('media_cats')
            ->values(array(
                'cat_name' => $model->getCatName()
            ))
            ->where(array('id' => $model->getCatId()))
            ->execute();
    }
}
