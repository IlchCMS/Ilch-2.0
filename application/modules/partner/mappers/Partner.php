<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Partner\Mappers;

use Modules\Partner\Models\Partner as PartnerModel;

class Partner extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.14.2
     */
    public $tablename = 'partners';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.14.2
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
     * @return PartnerModel[]|null
     * @since 1.14.2
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields('*')
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
            $entryModel = new PartnerModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Partner entries.
     *
     * @param array $where
     * @return PartnerModel[]
     */
    public function getEntries($where = [])
    {
        return $this->getPartnersBy($where, ['id' => 'DESC']);
    }

    /**
     * Gets partners.
     *
     * @param array $where
     * @param array $orderBy
     * @return PartnerModel[]
     */
    public function getPartnersBy($where = [], $orderBy = ['id' => 'ASC'])
    {
        $partnerArray = $this->getEntriesBy($where, $orderBy);

        if (!$partnerArray) {
            return [];
        }
        return $partnerArray;
    }

    /**
     * Gets partner.
     *
     * @param int $id
     * @return PartnerModel|null
     */
    public function getPartnerById($id)
    {
        $partnerArray = $this->getEntriesBy(['id' => $id], []);

        if ($partnerArray) {
            return reset($partnerArray);
        }
        return null;
    }

    /**
     * Inserts or updates partner model.
     *
     * @param PartnerModel $partner
     * @return int
     */
    public function save(PartnerModel $partner): int
    {
        $fields = $partner->getArray(false);

        if ($partner->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $partner->getId()])
                ->execute();
                return $partner->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Updates the position of a partner in the database.
     *
     * @param int $id
     * @param int $position
     */
    public function updatePositionById($id, $position)
    {
        $this->db()->update($this->tablename)
            ->values(['pos' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes partner with given id.
     *
     * @param int $id
     */
    public function delete($id)
    {
        $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.14.2
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
