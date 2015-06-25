<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Mappers;

use Modules\Media\Models\Media as MediaModel;

defined('ACCESS') or die('no direct access');

class Media extends \Ilch\Mapper
{
    /**
     * @var array Default criteria for opponents (default scope)
     */
    protected $default_criteria = array(
        'where' => null,
        'order' => array('m.name' => 'ASC'),
        'limit' => false,
    );

    public function getMediaList($criteria = null, $pagination = null)
    {
        $select = $this->db()->select();
        $result = $select->fields([ 'm.id', 'm.url', 'm.url_thumb', 'm.name', 'm.datetime', 'm.ending', 'm.cat'])
            ->from(['m' => 'media'])
            ->join(['c' => 'media_cats'], 'm.cat = c.id', 'LEFT', ['c.cat_name']);

        $dbCriteria = $this->default_criteria;
        if ($criteria !== null && is_array($criteria)) {
            $dbCriteria = array_merge($dbCriteria, $criteria);
        }
        if ($dbCriteria['where']) {
            $result->where($dbCriteria['where']);
        }
        if ($dbCriteria['order']) {
            $result->order($dbCriteria['order']);
        }
        if ($dbCriteria['limit']) {
            $result->limit($dbCriteria['limit']);
        }
        if ($pagination !== null) {
            $result->limit($pagination->getLimit());
            $pagination->setRows($result->getNumRows());
        }

        $result = $result->execute();

        if ($result->getNumRows() === 0) {
            return null;
        }

        $media = array();

        while ($row = $result->fetchAssoc()) {
            $entryModel = new MediaModel();
            $entryModel->setId($row['id']);
            $entryModel->setUrl($row['url']);
            $entryModel->setUrlThumb($row['url_thumb']);
            $entryModel->setName($row['name']);
            $entryModel->setDatetime($row['datetime']);
            $entryModel->setEnding($row['ending']);
            $entryModel->setCatName(($row['cat_name']));
            $entryModel->setCatId(($row['cat']));
            $media[] = $entryModel;
        }

        return $media;
    }

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

    public function save(MediaModel $model)
    {
        $this->db()->insert('media')
            ->values(array(
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'name' => $model->getName(),
                'datetime' => $model->getDatetime(),
                'ending' => $model->getEnding(),
                'cat' => $model->getCatId() == 0 ? 0 : $model->getCatId(),
                'cat_name' => 'Allgemein',
            ))
            ->execute();
    }

    public function delImage($id)
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
}
