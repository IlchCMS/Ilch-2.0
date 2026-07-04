<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Mappers;

use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;
use Modules\User\Mappers\User as UserMapper;

class Shoutbox extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.5.0
     */
    public string $tablename = 'shoutbox';

    /**
     * Check if DB-Table exists
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     *  @since 1.5.0
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @param string $search filters entries by name or text (since 1.8.0)
     * @return ShoutboxModel[]|array
     *  @since 1.5.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?\Ilch\Pagination $pagination = null, string $search = ''): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
            ->where($where)
            ->order($orderBy);

        if ($search !== '') {
            // Escape LIKE wildcards so they are matched literally.
            $like = '%' . addcslashes($search, '\\%_') . '%';
            $select->andWhere($select->orX(['name LIKE' => $like, 'textarea LIKE' => $like]));
        }

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
            return [];
        }

        $entries = [];

        foreach ($entryArray as $rows) {
            $entryModel = new ShoutboxModel();
            $entryModel->setByArray($rows);

            $entries[] = $entryModel;
        }

        return $entries;
    }

    /**
     * Gets the Shoutbox.
     *
     * @return ShoutboxModel[]|null
     */
    public function getShoutbox(): ?array
    {
        return $this->getEntriesBy([], ['id' => 'DESC']);
    }

    /**
     * Gets the Shoutbox.
     *
     * @param int|null $limit
     * @return ShoutboxModel[]|null
     */
    public function getShoutboxLimit(?int $limit = null): ?array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new \Ilch\Pagination();
            $pagination->setRowsPerPage($limit);
        }
        return $this->getEntriesBy([], ['id' => 'DESC'], $pagination);
    }

    /**
     * Gets a shoutbox entry by its id.
     *
     * @param int $id
     * @return ShoutboxModel|null
     * @since 1.8.0
     */
    public function getEntryById(int $id): ?ShoutboxModel
    {
        $entries = $this->getEntriesBy(['id' => $id]);

        return $entries[0] ?? null;
    }

    /**
     * Gets the users of the given entries with a single query instead of one per entry.
     * Guest entries (uid 0) are skipped.
     *
     * @param ShoutboxModel[] $entries
     * @return \Modules\User\Models\User[] user id => user model
     * @since 1.8.0
     */
    public function getUsersOfEntries(array $entries): array
    {
        $userIds = [];
        foreach ($entries as $entry) {
            if ($entry->getUid()) {
                $userIds[$entry->getUid()] = $entry->getUid();
            }
        }

        if (empty($userIds)) {
            return [];
        }

        $users = [];
        foreach ((new UserMapper())->getUserList(['id' => array_values($userIds)]) ?? [] as $user) {
            $users[$user->getId()] = $user;
        }

        return $users;
    }

    /**
     * Gets the time of the latest entry of a user.
     *
     * @param int $userId
     * @return string|null
     * @since 1.8.0
     */
    public function getLastPostTimeOfUser(int $userId): ?string
    {
        $time = $this->db()->select(['time'])
            ->from($this->tablename)
            ->where(['user_id' => $userId])
            ->order(['id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchCell();

        return $time ?: null;
    }

    /**
     * Insert shoutbox model.
     *
     * @param ShoutboxModel $shoutbox
     * @return int
     */
    public function save(ShoutboxModel $shoutbox): int
    {
        $fields = $shoutbox->getArray(false);

        if ($shoutbox->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $shoutbox->getId()])
                ->execute();
            return $shoutbox->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes shoutbox with given id.
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

    /**
     * Deletes all shoutbox entries.
     *
     * @return bool
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
