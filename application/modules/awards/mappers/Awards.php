<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Mappers;

use Modules\Awards\Models\Awards as AwardsModel;

class Awards extends \Ilch\Mapper
{
    /**
     * Gets the Awards entries.
     *
     * @param array $where
     * @return AwardsModel[]|array
     */
    public function getAwards($where = [])
    {
        $awardsArray = $this->db()->select('*')
            ->from('awards')
            ->where($where)
            ->order(['date' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($awardsArray)) {
            return [];
        }

        $awards = [];
        foreach ($awardsArray as $entries) {
            $awardsModel = new AwardsModel();
            $awardsModel->setId($entries['id'])
                ->setDate($entries['date'])
                ->setRank($entries['rank'])
                ->setImage($entries['image'])
                ->setEvent($entries['event'])
                ->setURL($entries['url'])
                ->setUTId($entries['ut_id'])
                ->setTyp($entries['typ']);
            $awards[] = $awardsModel;
        }

        return $awards;
    }

    /**
     * Gets awards.
     *
     * @param integer $id
     * @return AwardsModel|null
     */
    public function getAwardsById($id)
    {
        $awardsRow = $this->db()->select('*')
            ->from('awards')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($awardsRow)) {
            return null;
        }

        $awardsModel = new AwardsModel();
        $awardsModel->setId($awardsRow['id'])
            ->setDate($awardsRow['date'])
            ->setRank($awardsRow['rank'])
            ->setImage($awardsRow['image'])
            ->setEvent($awardsRow['event'])
            ->setURL($awardsRow['url'])
            ->setUTId($awardsRow['ut_id'])
            ->setTyp($awardsRow['typ']);

        return $awardsModel;
    }

    /**
     * Inserts or updates awards model.
     *
     * @param AwardsModel $awards
     */
    public function save(AwardsModel $awards)
    {
        $fields = [
            'date' => $awards->getDate(),
            'rank' => $awards->getRank(),
            'image' => $awards->getImage(),
            'event' => $awards->getEvent(),
            'url' => $awards->getURL(),
            'ut_id' => $awards->getUTId(),
            'typ' => $awards->getTyp()
        ];

        if ($awards->getId()) {
            $this->db()->update('awards')
                ->values($fields)
                ->where(['id' => $awards->getId()])
                ->execute();
        } else {
            $this->db()->insert('awards')
                ->values($fields)
                ->execute();
        }
    }

    public function existsTable($table)
    {
        $check = $this->db()->ifTableExists('[prefix]_'.$table);

        return $check;
    }

    /**
     * Deletes awards with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('awards')
            ->where(['id' => $id])
            ->execute();
    }
}
