<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Mappers;

use Modules\Teams\Models\Joins as JoinsModel;

class Joins extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.22.0
     */
    public $tablename = 'teams_joins';

    /**
     * Check if DB-Table exists
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.22.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Get Joins by given Params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|null
     * @since 1.22.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
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

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }

        $entries = [];

        foreach ($entryArray as $rows) {
            $entryModel = new JoinsModel();
            $entryModel->setByArray($rows);

            $entries[] = $entryModel;
        }

        return $entries;
    }

    /**
     * Get Join by given Id.
     *
     * @param int|JoinsModel $id
     * @return null|JoinsModel
     * @since 1.22.0
     */
    public function getEntryById($id): ?JoinsModel
    {
        if (is_a($id, JoinsModel::class)) {
            $id = $id->getId();
        }

        $entries = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets the Joins.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|null
     */
    public function getApplications(array $where = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getEntriesBy($where, ['id' => 'ASC'], $pagination);
    }

    /**
     * Gets the Joins.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|null
     */
    public function getJoins(array $where = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        $whereMerged = array_merge($where, ['undecided' => 1]);
        return $this->getApplications($whereMerged, $pagination);
    }

    /**
     * Gets the history of applications.
     *
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|null
     */
    public function getApplicationHistory(?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getApplications(['undecided' => 0], $pagination);
    }

    /**
     * Get Join by given Id.
     *
     * @param int|JoinsModel $id
     * @return JoinsModel|null
     */
    public function getJoinById($id): ?JoinsModel
    {
        return $this->getEntryById($id);
    }

    /**
     * Get Join in history by given Id.
     *
     * @param int|JoinsModel $id
     * @return JoinsModel|null
     */
    public function getJoinInHistoryById($id): ?JoinsModel
    {
        if (is_a($id, JoinsModel::class)) {
            $id = $id->getId();
        }

        $entries = $this->getApplications(['id' => (int)$id, 'undecided' => 0]);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets the history of applications of a specific user by userId.
     *
     * @param int $userId
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|null
     */
    public function getApplicationHistoryByUserId(int $userId, ?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getApplications(['userId' => $userId, 'undecided' => 0], $pagination);
    }

    /**
     * Get Age from date.
     *
     * @param string|\ilch\Date $date
     * @return int
     */
    public function getAge($date): int
    {
        if (!is_a($date, \ilch\Date::class)) {
            $date = new \ilch\Date($date);
        }
        return (int)$date->format('Y') - 1970;
    }

    /**
     * Update status of join/application
     * 1 = accepted, 2 = declined
     *
     * @param int|JoinsModel $id
     * @param int $decision
     * @return bool
     */
    public function updateDecision($id, int $decision): bool
    {
        if (is_a($id, JoinsModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['decision' => $decision, 'undecided' => 0])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts Join Model.
     *
     * @param JoinsModel $model
     * @return int
     */
    public function save(JoinsModel $model): int
    {
        $fields = $model->getArray(false);

        if ($model->getId()) {
            return $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete Join with given Id.
     *
     * @param int|JoinsModel $id
     * @return bool
     */
    public function delete($id): bool
    {
        if (is_a($id, JoinsModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete all applications/joins from the history.
     * In other words these are the ones with undecided = 0 (accept, reject).
     *
     * @return bool
     */
    public function clearHistory(): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['undecided' => 0])
            ->execute();
    }
}
