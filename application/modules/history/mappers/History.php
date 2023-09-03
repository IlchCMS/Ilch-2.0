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
     * @var string
     */
    public $tablename = 'history';

    /**
     * @return boolean
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return HistoryModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['date' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select()
            ->fields(['*'])
            ->from([$this->tablename])
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entryArray) {
            $entryModel = new HistoryModel();

            $entryModel->setByArray($entryArray);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the History entries.
     *
     * @param array $where
     * @return HistoryModel[]|array
     */
    public function getEntries(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets historys.
     *
     * @param array $where
     * @param array $orderBy
     * @return HistoryModel[]|null
     */
    public function getHistorysBy(array $where = [], array $orderBy = ['id' => 'ASC']): ?array
    {
        return $this->getEntriesBy($where, $orderBy);
    }

    /**
     * Gets history.
     *
     * @param int $id
     * @return HistoryModel|null
     */
    public function getHistoryById(int $id): ?HistoryModel
    {
        $entries = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Inserts or updates history model.
     *
     * @param HistoryModel $history
     * @return int
     */
    public function save(HistoryModel $history): int
    {
        $fields = $history->getArray(false);

        if ($history->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $history->getId()])
                ->execute();
            return $history->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes history with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
