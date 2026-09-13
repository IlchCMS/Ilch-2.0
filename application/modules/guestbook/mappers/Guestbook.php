<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Mappers;

use Modules\Guestbook\Models\Entry as GuestbookModel;

class Guestbook extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.14.4
     */
    public string $tablename = 'gbook';

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return GuestbookModel[]|null
     * @since 1.14.4
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
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
        $entriesArray = [];

        foreach ($entryArray as $entries) {
            $entryModel = new GuestbookModel();
            $entryModel->setByArray($entries);

            $entriesArray[] = $entryModel;
        }
        return $entriesArray;
    }

    /**
     * Gets the guestbook entries.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return GuestbookModel[]|array
     */
    public function getEntries(array $where = [], ?\Ilch\Pagination $pagination = null): array
    {
        $entryArray = $this->getEntriesBy($where, ['id' => 'DESC'], $pagination);

        if (!$entryArray) {
            return [];
        }
        return $entryArray;
    }

    /**
     * Updates or toggles the setfree flag for a guestbook entry.
     *
     * Pass a value of 0 or 1 to explicitly set the state.
     * Omit the second argument (or pass -1) to toggle the current state.
     *
     * @param int|GuestbookModel $id
     * @param int $setfree 0, 1, or -1 (toggle)
     * @return bool true if a row was updated, false otherwise
     */
    public function updateSetfree(int|GuestbookModel $id, int $setfree = -1): bool
    {
        if ($id instanceof GuestbookModel) {
            $id = $id->getId();
        }

        // Verify the entry actually exists.
        $currentFree = $this->db()->select('setfree')
            ->from($this->tablename)
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        if ($currentFree === null) {
            return false;
        }

        $currentFree = (int) $currentFree;

        if ($setfree === -1) {
            // Toggle: flip the current value.
            $newFree = ($currentFree === 1) ? 0 : 1;
        } elseif ($setfree === 0 || $setfree === 1) {
            $newFree = $setfree;
        } else {
            throw new \InvalidArgumentException('setfree must be 0, 1, or -1 (toggle).');
        }

        return $this->db()->update($this->tablename)
            ->values(['setfree' => $newFree])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates gustebook entry.
     *
     * @param GuestbookModel $model
     * @return int
     */
    public function save(GuestbookModel $model): int
    {
        $fields = $model->getArray(false);

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
                return $model->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes the guestbook entry.
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
     * Truncates or delete guestbook entries
     *
     * @param int|null $setfree
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.11.0
     */
    public function reset(?int $setfree = null): bool
    {
        if ($setfree === null) {
            $this->db()->truncate($this->tablename);
            return $this->db()->queryMulti('ALTER TABLE `[prefix]_' . $this->tablename . '` auto_increment = 1;');
        } else {
            return $this->db()->delete($this->tablename)
                ->where(['setfree' => $setfree])
                ->execute();
        }
    }
}
