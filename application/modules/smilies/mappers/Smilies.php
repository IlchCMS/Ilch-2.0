<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Mappers;

use Modules\Smilies\Models\Smilies as SmiliesModel;

class Smilies extends \Ilch\Mapper
{
    /**
     * Gets the smilies entries.
     *
     * @return SmiliesModel[]|array
     */
    public function getSmilies()
    {
        $array = $this->db()->select('*')
            ->from('smilies')
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $smilies = [];
        foreach ($array as $entries) {
            $model = new SmiliesModel();
            $model->setId($entries['id']);
            $model->setName($entries['name']);
            $model->setUrl($entries['url']);
            $smilies[] = $model;
        }

        return $smilies;
    }

    /**
     * Gets smilies by id.
     *
     * @param integer $id
     * @return SmiliesModel|null
     */
    public function getSmilieById($id)
    {
        $row = $this->db()->select('*')
            ->from('smilies')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($row)) {
            return null;
        }

        $model = new SmiliesModel();
        $model->setId($row['id']);
        $model->setName($row['name']);
        $model->setUrl($row['url']);

        return $model;
    }

    /**
     * Inserts or updates smilies.
     *
     * @param SmiliesModel $smilies
     */
    public function save(SmiliesModel $smilies)
    {
        $row = $this->db()->select('*')
            ->from('smilies')
            ->where(['url' => $smilies->getUrl()])
            ->execute()
            ->fetchAssoc();

        $fields = [
            'name' => $smilies->getName(),
            'url' => $smilies->getUrl(),
            'url_thumb' => $row['url_thumb'],
            'ending' => $row['endung']
        ];

        if ($smilies->getId()) {
            $this->db()->update('smilies')
                ->values($fields)
                ->where(['id' => $smilies->getId()])
                ->execute();
        }
    }

    /**
     * Inserts Smilies
     *
     * @param SmiliesModel $model
     */
    public function saveUpload(SmiliesModel $model)
    {
        $this->db()->insert('smilies')
            ->values([
                'name' => $model->getName(),
                'url' => $model->getUrl(),
                'url_thumb' => $model->getUrlThumb(),
                'ending' => $model->getEnding(),
            ])
            ->execute();
    }

    /**
     * Gets the Smilies Lists by ending.
     *
     * @param string $ending
     * @param \Ilch\Pagination|null $pagination
     * @return SmiliesModel[]|array
     */
    public function getSmiliesListByEnding($ending = NULL, $pagination = NULL) 
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                FROM `[prefix]_smilies`
                WHERE ending IN ("'.implode(',', [str_replace(' ', '","', $ending)]).'")
                ORDER by id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $array = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($array)) {
            return null;
        }

        $smilies = [];
        foreach ($array as $smilie) {
            $model = new SmiliesModel();
            $model->setId($smilie['id']);
            $model->setName($smilie['name']);
            $model->setUrl($smilie['url']);
            $model->setUrlThumb($smilie['url_thumb']);
            $model->setEnding($smilie['ending']);
            $smilies[] = $model;
        }

        return $smilies;
    }

    /**
     * Gets the Smilies List Scroll.
     *
     * @param int $lastId
     * @return SmiliesModel[]|array
     */
    public function getSmiliesListScroll($lastId = NULL) 
    {
        $sql = 'SELECT *
                FROM `[prefix]_smilies`
                WHERE id < '.$lastId.'
                ORDER by id DESC
                LIMIT 40';

        $array = $this->db()->query($sql);

        if (empty($array)) {
            return null;
        }

        $smilies = [];
        foreach ($array as $smilie) {
            $model = new SmiliesModel();
            $model->setId($smilie['id']);
            $model->setName($smilie['name']);
            $model->setUrl($smilie['url']);
            $model->setUrlThumb($smilie['url_thumb']);
            $model->setEnding($smilie['ending']);
            $smilies[] = $model;
        }

        return $smilies;
    }

    /**
     * Deletes smilies with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $row = $this->db()->select('*')
            ->from('smilies')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($row['url'])) {
            unlink($row['url']);
        }

        if (file_exists($row['url_thumb'])) {
            unlink($row['url_thumb']);
        }

        $this->db()->delete('smilies')
            ->where(['id' => $id])
            ->execute();
    }
}
