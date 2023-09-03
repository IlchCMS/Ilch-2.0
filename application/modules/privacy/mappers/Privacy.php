<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Privacy\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Pagination;
use Modules\Privacy\Models\Privacy as PrivacyModel;

class Privacy extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'privacy';

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
     * @return PrivacyModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['position' => 'ASC'], ?Pagination $pagination = null): ?array
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

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entry) {
            $entryModel = new PrivacyModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Privacy.
     *
     * @param array $where
     * @return PrivacyModel[]|null
     */
    public function getPrivacy(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets privacy.
     *
     * @param int $id
     * @return PrivacyModel|null
     */
    public function getPrivacyById(int $id): ?PrivacyModel
    {
        $entries = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Sort privacy.
     *
     * @param int $id
     * @param int $position
     * @return bool
     */
    public function sort(int $id, int $position): bool
    {
        return $this->db()->update($this->tablename)
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates privacy model.
     *
     * @param PrivacyModel $privacy
     * @return int
     */
    public function save(PrivacyModel $privacy): int
    {
        $fields = $privacy->getArray();

        if ($privacy->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $privacy->getId()])
                ->execute();
            return $privacy->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Updates privacy with given id.
     *
     * @param int $id
     * @param int $showMan
     * @return Result|int
     */
    public function update(int $id, int $showMan = -1)
    {
        if ($showMan != -1) {
            $showNow = $showMan;
        } else {
            $setFree = (int) $this->db()->select('show')
                ->from($this->tablename)
                ->where(['id' => $id])
                ->execute()
                ->fetchCell();

            if ($setFree == 1) {
                $showNow = 0;
            } else {
                $showNow = 1;
            }
        }
        return $this->db()->update($this->tablename)
            ->values(['show' => $showNow])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes privacy with given id.
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
