<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Imprint\Mappers;

use Ilch\Pagination;
use Modules\Imprint\Models\Imprint as ImprintModel;

class Imprint extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'imprint';

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
     * @return ImprintModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?Pagination $pagination = null): ?array
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
            $entryModel = new ImprintModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Imprint.
     *
     * @param array $where
     * @return ImprintModel[]|array
     */
    public function getImprint(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets imprint.
     *
     * @param int $id
     * @return ImprintModel|null
     */
    public function getImprintById(int $id): ?ImprintModel
    {
        $entries = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Updates imprint model.
     *
     * @param ImprintModel $imprint
     * @return int
     */
    public function save(ImprintModel $imprint): int
    {
        $fields = $imprint->getArray();

        if (!$this->getImprintById($imprint->getId())) {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        } else {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $imprint->getId()])
                ->execute();
        }
        return $imprint->getId();
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string $value
     * @param int|null $id
     * @return int
     */
    public function set(string $value, ?int $id = null): int
    {
        $entryModel = new ImprintModel();
        if ($id) {
            $entryModel->setId($id);
        }
        $entryModel->setImprint($value);
        return $this->save($entryModel);
    }
}
