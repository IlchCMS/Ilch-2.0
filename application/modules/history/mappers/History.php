<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Mappers;

use Modules\History\Models\Entry as HistoryModel;

class History extends \Ilch\Mapper
{
    /**
     * Gets the History entries.
     *
     * @param array $where
     * @return HistoryModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('history')
            ->where($where)
            ->order(array('date' => 'ASC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new HistoryModel();
            $entryModel->setId($entries['id']);
            $entryModel->setDate($entries['date']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setColor($entries['color']);
            $entryModel->setTyp($entries['typ']);
            $entryModel->setText($entries['text']);
            $entry[] = $entryModel;

        }

        return $entry;
    }
    
    /**
     * Gets historys.
     *
     * @param array $where
     * @param array $orderBy
     * @return HistoryModel[]|null
     */
    public function getHistorysBy($where = array(), $orderBy = array('id' => 'ASC'))
    {
        $historyArray = $this->db()->select('*')
            ->from('history')
            ->where($where)
            ->order($orderBy)
            ->execute()
            ->fetchRows();

        if (empty($historyArray)) {
            return null;
        }

        $historys = array();

        foreach ($historyArray as $historyRow) {
            $historyModel = new HistoryModel();
            $historyModel->setId($historyRow['id']);
            $historyModel->setDate($historyRow['date']);
            $historyModel->setTitle($historyRow['title']);
            $historyModel->setColor($historyRow['color']);
            $historyModel->setTyp($historyRow['typ']);
            $historyModel->setText($historyRow['text']);
            $historys[] = $historyModel;
        }

        return $historys;
    }

    /**
     * Gets history.
     *
     * @param integer $id
     * @return HistoryModel|null
     */
    public function getHistoryById($id)
    {
        $historyRow = $this->db()->select('*')
            ->from('history')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($historyRow)) {
            return null;
        }

        $historyModel = new HistoryModel();
        $historyModel->setId($historyRow['id']);
        $historyModel->setDate($historyRow['date']);
        $historyModel->setTitle($historyRow['title']);
        $historyModel->setColor($historyRow['color']);
        $historyModel->setTyp($historyRow['typ']);
        $historyModel->setText($historyRow['text']);

        return $historyModel;
    }

    /**
     * Inserts or updates history model.
     *
     * @param HistoryModel $history
     */
    public function save(HistoryModel $history)
    {
        $fields = array
        (
            'date' => $history->getDate(),
            'title' => $history->getTitle(),
            'text' => $history->getText(),
        );

        if ($history->getId()) {
            $this->db()->update('history')
                ->values($fields)
                ->where(array('id' => $history->getId()))
                ->execute();
        } else {
            $this->db()->insert('history')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes history with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('history')
            ->where(array('id' => $id))
            ->execute();
    }
}
