<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Mappers;

use Modules\Teams\Models\Teams as TeamsModel;

class Teams extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.22.0
     */
    public $tablename = 'teams';

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
     * Get Teams by given Params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return TeamsModel[]|null
     * @since 1.22.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['position' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
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
            $entryModel = new TeamsModel();
            $entryModel->setByArray($rows);

            $entries[] = $entryModel;
        }

        return $entries;
    }

    /**
     * Get Team by given Id.
     *
     * @param int|TeamsModel $id
     * @return null|TeamsModel
     * @since 1.22.0
     */
    public function getEntryById($id): ?TeamsModel
    {
        if (is_a($id, TeamsModel::class)) {
            $id = $id->getId();
        }

        $entries = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets the Teams.
     *
     * @param array $where
     * @return TeamsModel[]|null
     */
    public function getTeams(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Get Team by given Id.
     *
     * @param int|TeamsModel $id
     * @return TeamsModel|null
     */
    public function getTeamById($id): ?TeamsModel
    {
        return $this->getEntryById($id);
    }

    /**
     * Get Team by given group id.
     *
     * @param int|TeamsModel $groupId
     * @return TeamsModel|null
     */
    public function getTeamByGroupId($groupId): ?TeamsModel
    {
        if (is_a($groupId, TeamsModel::class)) {
            $groupId = $groupId->getGroupId();
        }

        $entries = $this->getEntriesBy(['groupId' => $groupId], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Delete/Unlink Image by Id.
     *
     * @param int|TeamsModel $id
     * @param bool $noUpdate
     * @return bool
     */
    public function delImageById($id, bool $noUpdate = false): bool
    {
        if (is_a($id, TeamsModel::class)) {
            $entry = $id;
        } else {
            $entry = $this->getTeamById($id);
        }

        if (!$entry) {
            return false;
        }

        if (file_exists($entry->getImg())) {
            unlink($entry->getImg());
        }

        if (!$noUpdate) {
            $entry->setImg('');
            return $this->save($entry);
        } else {
            return (!file_exists($entry->getImg()));
        }
    }

    /**
     * Sort teams.
     *
     * @param int|TeamsModel $id
     * @param int $pos
     * @return bool
     */
    public function sort($id, int $pos): bool
    {
        if (is_a($id, TeamsModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['position' => $pos])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates Team Model.
     *
     * @param TeamsModel $model
     * @return int
     */
    public function save(TeamsModel $model): int
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
     * Delete Team with given Id.
     *
     * @param int|TeamsModel $id
     * @return bool
     */
    public function delete($id): bool
    {
        if (is_a($id, TeamsModel::class)) {
            $entry = $id;
        } else {
            $entry = $this->getTeamById($id);
        }

        if (!$entry) {
            return false;
        }

        $this->delImageById($entry, true);

        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
