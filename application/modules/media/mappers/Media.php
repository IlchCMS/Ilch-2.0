<?php
/**
 * @copyright Ilch 2
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
     * @param string $sortOder ASC or DESC
     * @return MediaModel[]|array
     */
    public function getMediaList($pagination = null, $sortOder = 'DESC')
    {
        $select = $this->db()->select(['m.id', 'm.url', 'm.url_thumb', 'm.name', 'm.datetime', 'm.ending', 'm.cat', 'c.cat_name'])
            ->from(['m' => 'media'])
            ->join(['c' => 'media_cats'], 'm.cat = c.id', 'LEFT')
            ->order(['m.id' => ($sortOder !== 'DESC') ? 'ASC' : 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $mediaArray = $result->fetchRows();

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
     * @throws \Ilch\Database\Exception
     */
    public function getMediaListAll()
    {
        $mediaArray = $this->db()->select(['m.id', 'm.url', 'm.url_thumb', 'm.name', 'm.datetime', 'm.ending', 'm.cat', 'c.cat_name'])
            ->from(['m' => 'media'])
            ->join(['c' => 'media_cats'], 'm.cat = c.id', 'LEFT')
            ->order(['m.id' => 'DESC'])
            ->execute()
            ->fetchRows();

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
     * @param string $sortOder Either ASC or DESC
     * @return MediaModel[]|array
     */
    public function getMediaListByEnding($ending = null, $pagination = null, $sortOder = 'DESC')
    {
        $select = $this->db()->select(['m.id', 'm.url', 'm.url_thumb', 'm.name', 'm.datetime', 'm.ending', 'm.cat', 'c.cat_name'])
            ->from(['m' => 'media'])
            ->join(['c' => 'media_cats'], 'm.cat = c.id', 'LEFT')
            ->where(['m.ending' => $this->db()->escapeArray(explode(' ', $ending))])
            ->order(['m.id' => ($sortOder !== 'DESC') ? 'ASC' : 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $mediaArray = $result->fetchRows();

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
     * @throws \Ilch\Database\Exception
     */
    public function getMediaListScroll($lastId = NULL)
    {
        $mediaArray = $this->db()->select(['m.id', 'm.url', 'm.url_thumb', 'm.name', 'm.datetime', 'm.ending', 'm.cat', 'c.cat_name'])
            ->from(['m' => 'media'])
            ->join(['c' => 'media_cats'], 'm.cat = c.id', 'LEFT')
            ->where(['m.id <' => (int)$lastId])
            ->order(['m.id' => 'DESC'])
            ->limit(40)
            ->execute()
            ->fetchRows();

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
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($mediaArray)) {
            return [];
        }

        $media = [];

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
        return $this->getCatByWhere(['id' => $id]);
    }

    /**
     * Get cat by name.
     *
     * @param $name
     * @return MediaModel|null
     */
    public function getCatByName($name)
    {
        return $this->getCatByWhere(['cat_name' => $name]);
    }

    private function getCatByWhere($where = [])
    {
        $catRow = $this->db()->select('*')
            ->from('media_cats')
            ->where($where)
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

    /**
     * Get media by where.
     * Returns only a single item.
     *
     * @param array $where
     * @return MediaModel|null
     */
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
        $mediaModel->setId($mediaRow['id']);
        $mediaModel->setUrl($mediaRow['url']);
        $mediaModel->setUrlThumb($mediaRow['url_thumb']);
        $mediaModel->setName($mediaRow['name']);
        $mediaModel->setDatetime($mediaRow['datetime']);
        $mediaModel->setEnding($mediaRow['ending']);
        $mediaModel->setCatId(($mediaRow['cat']));

        return $mediaModel;
    }

    /**
     * Inserts Media
     *
     * @param MediaModel $model
     */
    public function save(MediaModel $model)
    {
        $id = (int)$this->db()->select('id')
            ->from('media')
            ->where(['id' => $model->getId()])
            ->execute()
            ->fetchCell();

        if ($id) {
            $this->db()->update('media')
                ->values([
                    'id' => $model->getId(),
                    'url' => $model->getUrl(),
                    'url_thumb' => $model->getUrlThumb(),
                    'name' => $model->getName(),
                    'datetime' => $model->getDatetime(),
                    'ending' => $model->getEnding(),
                    'cat' => '0',
                ])
                ->where(['id' => $id])
                ->execute();
        } else {
            $catId = ($model->getCatId()) ?: 0;
            $this->db()->insert('media')
                ->values([
                    'url' => $model->getUrl(),
                    'url_thumb' => $model->getUrlThumb(),
                    'name' => $model->getName(),
                    'datetime' => $model->getDatetime(),
                    'ending' => $model->getEnding(),
                    'cat' => $catId,
                ])
                ->execute();
        }
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
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();
        if (file_exists($mediaRow['url'])) {
            unlink($mediaRow['url']);
        }
        if (file_exists($mediaRow['url_thumb'])) {
            unlink($mediaRow['url_thumb']);
        }
        $this->db()->delete('media')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts Cat
     *
     * @param MediaModel $model
     * @return int id
     */
    public function saveCat(MediaModel $model)
    {
        $this->db()->insert('media_cats')
            ->values([
                'cat_name' => $model->getCatName()
            ])
            ->execute();

        return $this->db()->getLastInsertId();
    }

    /**
     * Delete Cat by id.
     *
     * @param int $id
     */
    public function delCatById($id)
    {
        $this->db()->delete('media_cats')
            ->where(['id' => $id])
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
            ->values([
                'cat' => $model->getCatId()
            ])
            ->where(['id' => $model->getId()])
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
            ->values([
                'cat_name' => $model->getCatName()
            ])
            ->where(['id' => $model->getCatId()])
            ->execute();
    }
}
