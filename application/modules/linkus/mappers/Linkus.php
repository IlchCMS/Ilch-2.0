<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Linkus\Mappers;

use Modules\Linkus\Models\Linkus as LinkusModel;

class Linkus extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.7.3
     */
    public $tablename = 'linkus';

    /**
     * returns if the module is installed.
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.7.3
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
     * @return LinkusModel[]|null
     * @since 1.7.3
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
            ->where($where)
            ->order($orderBy);;

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
            $entryModel = new LinkusModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Linkus.
     *
     * @param array $where
     * @return LinkusModel[]|null
     */
    public function getLinkus(array $where = [])
    {
        return $this->getEntriesBy($where, ['id' => 'ASC']);
    }

    /**
     * Gets Linkus.
     *
     * @param int $id
     * @return LinkusModel|null
     */
    public function getLinkusById(int $id)
    {
        $linkusRow = $this->getEntriesBy(['id' => $id], []);

        if ($linkusRow) {
            return reset($linkusRow);
        }
        return null;
    }

    /**
     * Inserts or updates Linkus model.
     *
     * @param LinkusModel $linkus
     * @return int
     */
    public function save(LinkusModel $linkus)
    {
        $fields = $linkus->getArray();

        if ($linkus->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $linkus->getId()])
                ->execute();
                return $linkus->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes linkus with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.7.3
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
