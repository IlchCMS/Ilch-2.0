<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Mappers;

use Ilch\Database\Mysql\Result;
use Modules\Guestbook\Models\Entry as GuestbookModel;

class Guestbook extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.14.4
     */
    public $tablename = 'gbook';

    /**
     * returns if the module is installed.
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.14.4
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
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new GuestbookModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
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
     * @param int|GuestbookModel $id
     * @param int $setfree
     * @return boolean
     */
    public function updateSetfree($id, int $setfree = -1): bool
    {
        if ($setfree !== -1) {
            $setfreeNow = $setfree;
        } else {
            if (is_a($id, GuestbookModel::class)) {
                $setfree = $id->getFree();
            } else {
                $setfree = (int) $this->db()->select('setfree')
                    ->from($this->tablename)
                    ->where(['id' => (int)$id])
                    ->execute()
                    ->fetchCell();
            }

            if ($setfree === 1) {
                $setfreeNow = 0;
            } else {
                $setfreeNow = 1;
            }
        }
        if (is_a($id, GuestbookModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update($this->tablename)
            ->values(['setfree' => $setfreeNow])
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Inserts or updates gustebook entry.
     *
     * @param GuestbookModel $model
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
     * Reset the Vote counts.
     *
     * @param int|null $setfree
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.11.0
     */
    public function reset(?int $setfree = null): bool
    {
        if ($setfree == null) {
            $this->db()->truncate($this->tablename);
            return $this->db()->queryMulti('ALTER TABLE `[prefix]_' . $this->tablename . '` auto_increment = 1;');
        } else {
            return $this->db()->delete($this->tablename)
                ->where(['setfree' => $setfree])
                ->execute();
        }
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.14.4
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
