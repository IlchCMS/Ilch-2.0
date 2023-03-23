<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Mappers;

use Modules\Teams\Models\Teams as EntriesModel;

class Teams extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.22.0
     */
    public $tablename = 'teams';

    /**
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.22.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]|null
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

        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }

        return $entrys;
    }

    /**
     * @param int|EntriesModel $id
     * @return null|EntriesModel
     * @since 1.22.0
     */
    public function getEntryById($id): ?EntriesModel
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        $entrys = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Gets the Teams.
     *
     * @param array $where
     * @return EntriesModel[]|null
     */
    public function getTeams(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Get Team by given Id.
     *
     * @param int|EntriesModel $id
     * @return EntriesModel|null
     */
    public function getTeamById($id): ?EntriesModel
    {
        return $this->getEntryById($id);
    }

    /**
     * Get Team by given group id.
     *
     * @param int|EntriesModel $groupId
     * @return EntriesModel|null
     */
    public function getTeamByGroupId($groupId): ?EntriesModel
    {
        if (is_a($groupId, EntriesModel::class)) {
            $groupId = $groupId->getGroupId();
        }

        $entrys = $this->getEntriesBy(['groupId' => $groupId], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Delete/Unlink Image by Id.
     *
     * @param int|EntriesModel $id
     * @param bool $noUpdate
     * @return bool
     */
    public function delImageById($id, bool $noUpdate = false): bool
    {
        if (is_a($id, EntriesModel::class)) {
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
     * @param int|EntriesModel $id
     * @param int $pos
     * @return bool
     */
    public function sort($id, int $pos): bool
    {
        if (is_a($id, EntriesModel::class)) {
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
     * @param EntriesModel $model
     * @return int
     */
    public function save(EntriesModel $model): int
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
     * @param int|EntriesModel $id
     * @return bool
     */
    public function delete($id): bool
    {
        if (is_a($id, EntriesModel::class)) {
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
