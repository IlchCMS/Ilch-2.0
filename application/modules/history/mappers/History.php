<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Mappers;

use Modules\History\Models\History as HistoryModel;

class History extends \Ilch\Mapper
{
    /**
     * Gets the History entries.
     *
     * @param array $where
     * @return HistoryModel[]|array
     */
    public function getEntries($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('history')
            ->where($where)
            ->order(['date' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new HistoryModel();
            $entryModel->setId($entries['id']);
            $entryModel->setDate($entries['date']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setType($entries['type']);
            $entryModel->setColor($entries['color']);
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
    public function getHistorysBy($where = [], $orderBy = ['id' => 'ASC'])
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

        $historys = [];
        foreach ($historyArray as $historyRow) {
            $historyModel = new HistoryModel();
            $historyModel->setId($historyRow['id']);
            $historyModel->setDate($historyRow['date']);
            $historyModel->setTitle($historyRow['title']);
            $historyModel->setType($historyRow['type']);
            $historyModel->setColor($historyRow['color']);
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
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($historyRow)) {
            return null;
        }

        $historyModel = new HistoryModel();
        $historyModel->setId($historyRow['id']);
        $historyModel->setDate($historyRow['date']);
        $historyModel->setTitle($historyRow['title']);
        $historyModel->setType($historyRow['type']);
        $historyModel->setColor($historyRow['color']);
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
        $fields = [
            'date' => $history->getDate(),
            'title' => $history->getTitle(),
            'type' => $history->getType(),
            'color' => $history->getColor(),
            'text' => $history->getText()
        ];

        if ($history->getId()) {
            $this->db()->update('history')
                ->values($fields)
                ->where(['id' => $history->getId()])
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
            ->where(['id' => $id])
            ->execute();
    }
}
