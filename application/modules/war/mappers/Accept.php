<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Mappers;

use Ilch\Mapper;
use Ilch\Pagination;
use Modules\War\Models\Accept as EntriesModel;

class Accept extends Mapper
{
    public string $tablename = 'war_accept';

    /**
     * returns if the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?Pagination $pagination = null): ?array
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

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entryModels = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entryModels[] = $entryModel;
        }
        return $entryModels;
    }

    /**
     * Gets the accept
     *
     * @param array $where
     * @return null|array
     */
    public function getAcceptByWhere(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets the accept list of all members of the group for the given war.
     *
     * Members without an accept entry are included as well (same result as a
     * LEFT JOIN on user_id / war_id); for them the model defaults apply
     * (id 0, warId 0, accept 0, empty comment/dateCreated).
     *
     * Implemented as two simple queries instead of a JOIN because the
     * QueryBuilder quotes field/where names as single identifiers and cannot
     * express qualified columns (table.column) or column aliases.
     *
     * @param int $groupId
     * @param int $warId
     * @return array
     */
    public function getAcceptListByGroupId(int $groupId, int $warId): array
    {
        // 1) All members of the group.
        $memberIds = $this->db()->select()
            ->fields(['user_id'])
            ->from(['users_groups'])
            ->where(['group_id' => $groupId])
            ->order(['user_id' => 'ASC'])
            ->execute()
            ->fetchList();

        if (empty($memberIds)) {
            return [];
        }

        // 2) All accepts for the war, indexed by user.
        $acceptRows = $this->db()->select()
            ->fields(['*'])
            ->from([$this->tablename])
            ->where(['war_id' => $warId])
            ->execute()
            ->fetchRows();

        $acceptByUserId = [];
        foreach ($acceptRows as $row) {
            $acceptByUserId[(int)$row['user_id']] = $row;
        }

        // 3) One model per member; missing accept rows map to the model
        //    defaults via the isset()-based setByArray().
        $entryModels = [];
        foreach ($memberIds as $userId) {
            $entryModel = new EntriesModel();
            $entryModel->setUserId((int)$userId);

            if (isset($acceptByUserId[(int)$userId])) {
                $entryModel->setByArray($acceptByUserId[(int)$userId]);
            }

            $entryModels[] = $entryModel;
        }

        return $entryModels;
    }

    /**
     * Inserts or updates entry.
     *
     * @param EntriesModel $model
     * @return int
     */
    public function save(EntriesModel $model): int
    {
        // date_created is DATETIME NOT NULL; always persist a valid datetime,
        // even if the caller never set it (would otherwise be '' and fail
        // under MySQL strict mode / store 0000-00-00).
        if ($model->getDateCreated() === '') {
            $model->setDateCreated(date('Y-m-d H:i:s'));
        }

        // Never write the id column explicitly:
        // - insert: it must be left to AUTO_INCREMENT (an explicit id = 0
        //   is rejected / stored as 0 under sql_mode NO_AUTO_VALUE_ON_ZERO),
        // - update: the id is used in the WHERE clause.
        $fields = $model->getArray(false);

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();

            return $model->getId();
        }

        return $this->db()->insert($this->tablename)
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes the entry.
     *
     * @param int|EntriesModel $id
     * @return bool
     */
    public function delete(EntriesModel|int $id): bool
    {
        if ($id instanceof EntriesModel) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Get Export JSON.
     *
     * @param int $options
     * @return string
     */
    public function getJson(int $options = 0): string
    {
        $entryArray = $this->getEntriesBy();
        $entries = [];

        if ($entryArray) {
            foreach ($entryArray as $entryModel) {
                $entries[] = $entryModel->getArray(false);
            }
        }

        return json_encode($entries, $options);
    }
}
