<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Mappers;

use Modules\Vote\Models\Vote as VoteModel;

class Vote extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.12.0
     */
    public string $tablename = 'poll';

    /**
     * @var string
     * @since 1.12.0
     */
    public string $tablenameReadAcces = 'poll_access';

    /**
     * returns if the module is installed.
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.12.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return VoteModel[]|null
     * @since 1.12.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        $read_access = '';
        if (isset($where['ra.read_access'])) {
            $read_access = $where['ra.read_access'];
            unset($where['ra.read_access']);
        }

        $select = $this->db()->select();
        $select->fields(['p.id', 'p.question', 'p.key', 'p.groups', 'p.status', 'p.read_access_all', 'p.multiple_reply'])
            ->from(['p' => $this->tablename])
            ->join(['ra' => $this->tablenameReadAcces], 'p.id = ra.poll_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($read_access ? [$select->orX(['ra.group_id' => $read_access, 'p.read_access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['p.id']);

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
            $entryModel = new VoteModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Vote.
     *
     * @param array $where
     * @param string|array|null $readAccess A string like '1,2,3' or an array like [1,2,3]
     * @return VoteModel[]|array
     */
    public function getVotes(array $where = [], $readAccess = '3'): ?array
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }

        return $this->getEntriesBy(array_merge($where, ($readAccess ? ['ra.read_access' => $readAccess] : [])), ['p.id' => 'DESC']);
    }

    /**
     * Gets Vote.
     *
     * @param int $id
     * @return VoteModel|null
     */
    public function getVoteById(int $id): ?VoteModel
    {
        $entrys = $this->getEntriesBy(['p.id' => $id]);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Updates vote status with given id.
     *
     * @param int $id
     * @param int $status
     * @return bool
     */
    public function lock(int $id, int $status = -1): bool
    {
        if ($status !== -1) {
            $statusNow = $status;
        } else {
            $status = (int) $this->db()->select('status')
                ->from($this->tablename)
                ->where(['id' => $id])
                ->execute()
                ->fetchCell();

            if ($status === 1) {
                $statusNow = 0;
            } else {
                $statusNow = 1;
            }
        }

        return $this->db()->update($this->tablename)
            ->values(['status' => $statusNow])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Updates vote status with given id.
     *
     * @return int
     */
    public function getLastId(): int
    {
        return $this->db()->select('MAX(id)')
            ->from($this->tablename)
            ->execute()
            ->fetchCell();
    }

    /**
     * Inserts or updates Vote model.
     *
     * @param VoteModel $vote
     * @return int
     */
    public function save(VoteModel $vote): int
    {
        $fields = $vote->getArray(false);

        if ($vote->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $vote->getId()])
                ->execute();
            $result = $vote->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveReadAccess($result, $vote->getReadAccess());

        return $result;
    }

    /**
     * Update the entries for which user groups are allowed to read a Vote.
     *
     * @param int $voteId
     * @param string|array $readAccess example: "1,2,3"
     * @param bool $addAdmin
     * @return array
     * @since 1.12.0
     */
    public function saveReadAccess(int $voteId, $readAccess, bool $addAdmin = true)
    {
        if (\is_string($readAccess)) {
            $readAccess = explode(',', $readAccess);
        }

        // Delete possible old entries to later insert the new ones.
        $this->db()->delete($this->tablenameReadAcces)
            ->where(['poll_id' => $voteId])
            ->execute();

        $groupIds = [];
        if (!empty($readAccess)) {
            if (!in_array('all', $readAccess)) {
                $groupIds = $readAccess;
            }
        }
        if ($addAdmin && !in_array('1', $groupIds)) {
            $groupIds[] = '1';
        }

        $preparedRows = [];
        foreach ($groupIds as $groupId) {
            $preparedRows[] = [$voteId, (int)$groupId];
        }

        if (count($preparedRows)) {
            // Add access rights in chunks of 25 to the table. This prevents reaching the limit of 1000 rows
            $chunks = array_chunk($preparedRows, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert($this->tablenameReadAcces)
                    ->columns(['poll_id', 'group_id'])
                    ->values($chunk)
                    ->execute();
            }
        }

        return $groupIds;
    }

    /**
     * Deletes Vote with given id.
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
