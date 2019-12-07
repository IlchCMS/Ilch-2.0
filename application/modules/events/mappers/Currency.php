<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Mappers;

use Modules\Events\Models\Currency as CurrencyModel;

class Currency extends \Ilch\Mapper
{
    /**
     * Gets the currencies.
     *
     * @param array $where
     *
     * @return CurrencyModel[]|array
     */
    public function getCurrencies($where = [])
    {
        $currenciesArray = $this->db()->select('*')
            ->from('events_currencies')
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
            $currencyModel->setId($currency['id'])
                ->setName($currency['name']);
            $currencies[] = $currencyModel;
        }

        return $currencies;
    }

    /**
     * Gets the currencies by id.
     *
     * @param int $id
     *
     * @return CurrencyModel[]|array
     */
    public function getCurrencyById($id)
    {
        return $this->getCurrencies(['id' => $id]);
    }

    /**
     * Insert or update currencies.
     *
     * @param CurrencyModel $model
     */
    public function save(CurrencyModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('events_currencies')
                ->values(['name' => $model->getName()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('events_currencies')
                ->values(['name' => $model->getName()])
                ->execute();
        }
    }

    /**
     * Deletes the currency by id.
     *
     * @param int $id
     */
    public function deleteCurrencyById($id)
    {
        $this->db()->delete('events_currencies')
            ->where(['id' => $id])
            ->execute();
    }
}
