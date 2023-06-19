<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Mappers;

use Modules\Checkoutbasic\Models\Entry as CheckoutModel;

class Checkout extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'checkoutbasic';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
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
     * @return CheckoutModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['date_created' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
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
            $entryModel = new CheckoutModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Checkout entries.
     *
     * @param array $where
     * @return CheckoutModel[]|array
     */
    public function getEntries(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }

    /**
     * @param int $id
     * @return null|CheckoutModel
     */
    public function getEntryById(int $id): ?CheckoutModel
    {
        $entrys = $this->getEntriesBy(['id' => $id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->db()->select('ROUND(SUM(amount),2)', $this->tablename)
            ->execute()
            ->fetchCell() ?? null;
    }

    /**
     * @return float|null
     */
    public function getAmountPlus(): ?float
    {
        return $this->db()->select('ROUND(SUM(amount),2)', $this->tablename, ['amount >' => 0])
            ->execute()
            ->fetchCell() ?? null;
    }

    /**
     * @return float|null
     */
    public function getAmountMinus(): ?float
    {
        return $this->db()->select('ROUND(SUM(amount),2)', $this->tablename, ['amount <' => 0])
            ->execute()
            ->fetchCell() ?? null;
    }

    /**
     * Inserts or updates Checkout entry.
     *
     * @param CheckoutModel $model
     * @return int
     */
    public function save(CheckoutModel $model): int
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
     * Deletes the Checkout entry.
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
