<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Mappers;

use Modules\Checkoutbasic\Models\Currency as CurrencyModel;

class Currency extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.5.0
     */
    public $tablename = 'checkoutbasic_currencies';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.5.0
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
     * @return CurrencyModel[]|null
     * @since 1.5.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['name' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
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
            $entryModel = new CurrencyModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the currencies.
     *
     * @param array $where
     * @return CurrencyModel[]|null
     */
    public function getCurrencies(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * Gets the currencies by id.
     *
     * @param int $id
     * @return CurrencyModel|null
     */
    public function getCurrencyById(int $id): ?CurrencyModel
    {
        $entrys = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Insert or update currencies.
     *
     * @param CurrencyModel $model
     * @return int
     */
    public function save(CurrencyModel $model): int
    {
        $fields = $model->getArray(false);

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * Deletes the currency by id.
     *
     * @param int $id
     * @return \Ilch\Database\Mysql\Result|int
     */
    public function deleteCurrencyById(int $id)
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
