<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Mappers;

use Modules\Checkout\Models\Currency as CurrencyModel;

class Currency extends \Ilch\Mapper
{
    /**
     * Gets the currencies.
     *
     * @param array $where
     * @return CurrencyModel[]|array
     */
    public function getCurrencies($where = [])
    {
        $currenciesArray = $this->db()->select('*')
            ->from('checkout_currencies')
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
            $currencies[] = $currencyModel;
        }

        return $currencies;
    }

    /**
     * Gets the currencies by id.
     *
     * @param int $id
     * @return CurrencyModel[]|array
     */
    public function getCurrencyById($id)
    {
        $currency = $this->getCurrencies(['id' => $id]);
        return $currency;
    }

    /**
     * Checks if a currency with a specific name exists.
     *
     * @param string $name
     * @return boolean
     */
    public function currencyWithNameExists($name)
    {
        return (boolean) $this->db()->select('COUNT(*)', 'checkout_currencies', ['name' => $name])
            ->execute()
            ->fetchCell();
    }

    /**
     * Insert or update currencies.
     *
     * @param CurrencyModel $model
     */
    public function save(CurrencyModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('checkout_currencies')
                ->values(['name' => $model->getName()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('checkout_currencies')
                ->values(['name' => $model->getName()])
                ->execute();
        }
    }

    /**
     * Deletes the currency by id.
     *
     * @param integer $id
     */
    public function deleteCurrencyById($id)
    {
        return $this->db()->delete('checkout_currencies')
            ->where(['id' => $id])
            ->execute();
    }
}
