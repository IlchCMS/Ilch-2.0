<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Currency as CurrencyModel;

class Currency extends Mapper
{
    /**
     * Gets the currencies.
     *
     * @param array $where
     * @return CurrencyModel[]|array
     */
    public function getCurrencies(array $where = []): array
    {
        $currenciesArray = $this->db()->select('*')
            ->from('shop_currencies')
            ->where($where)
            ->order(['name' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($currenciesArray)) {
            return [];
        }

        $currencies = [];

        foreach ($currenciesArray as $currency) {
            $currencyModel = new CurrencyModel();
            $currencyModel->setId($currency['id']);
            $currencyModel->setName($currency['name']);
            $currencyModel->setCode($currency['code']);
            $currencies[] = $currencyModel;
        }

        return $currencies;
    }

    /**
     * Gets the currencies by id.
     *
     * @param int $id
     * @return CurrencyModel|null
     */
    public function getCurrencyById(int $id): ?CurrencyModel
    {
        $currency = $this->getCurrencies(['id' => $id]);

        if (empty($currency)) {
            return null;
        }

        return reset($currency);
    }

    /**
     * Insert or update currencies.
     *
     * @param CurrencyModel $model
     * @return int ID of the saved currency.
     */
    public function save(CurrencyModel $model): int
    {
        if ($model->getId()) {
            $this->db()->update('shop_currencies')
                ->values(['name' => $model->getName(), 'code' => $model->getCode()])
                ->where(['id' => $model->getId()])
                ->execute();

            return $model->getId();
        }

        return $this->db()->insert('shop_currencies')
            ->values(['name' => $model->getName(), 'code' => $model->getCode()])
            ->execute();
    }

    /**
     * Deletes the currency by id.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCurrencyById(int $id): bool
    {
        return (bool) $this->db()->delete('shop_currencies')
            ->where(['id' => $id])
            ->execute();
    }
}
