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
    public function getAwards($where = array())
    {
        $awardsArray = $this->db()->select('*')
            ->from('awards')
            ->where($where)
            ->order(array('date' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($awardsArray)) {
            return null;
        }

        $awards = array();

        foreach ($awardsArray as $entries) {
            $awardsModel = new AwardsModel();
            $awardsModel->setId($entries['id']);
            $awardsModel->setDate($entries['date']);
            $awardsModel->setRank($entries['rank']);
            $awardsModel->setEvent($entries['event']);
            $awardsModel->setURL($entries['url']);
            $awardsModel->setUTId($entries['ut_id']);
            $awardsModel->setTyp($entries['typ']);
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
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($awardsRow)) {
            return null;
        }

        $awardsModel = new AwardsModel();
        $awardsModel->setId($awardsRow['id']);
        $awardsModel->setDate($awardsRow['date']);
        $awardsModel->setRank($awardsRow['rank']);
        $awardsModel->setEvent($awardsRow['event']);
        $awardsModel->setURL($awardsRow['url']);
        $awardsModel->setUTId($awardsRow['ut_id']);
        $awardsModel->setTyp($awardsRow['typ']);

        return $awardsModel;
    }

    /**
     * Inserts or updates awards model.
     *
     * @param AwardsModel $awards
     */
    public function save(AwardsModel $awards)
    {
        $fields = array
        (
            'date' => $awards->getDate(),
            'rank' => $awards->getRank(),
            'event' => $awards->getEvent(),
            'url' => $awards->getURL(),
            'ut_id' => $awards->getUTId(),
            'typ' => $awards->getTyp(),
        );

        if ($awards->getId()) {
            $this->db()->update('awards')
                ->values($fields)
                ->where(array('id' => $awards->getId()))
                ->execute();
        } else {
            $this->db()->insert('awards')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes awards with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('awards')
            ->where(array('id' => $id))
            ->execute();
    }
}
