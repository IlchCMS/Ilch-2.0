<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Checkout\Mappers;

use Checkout\Models\Entry as CheckoutModel;

defined('ACCESS') or die('no direct access');

class Checkout extends \Ilch\Mapper
{
    /**
     * Gets the Checkout entries.
     *
     * @param array $where
     * @return CheckoutModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->selectArray('*')
            ->from('checkout')
            ->where($where)
            ->order(array('id' => 'DESC'))
            ->execute();

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new CheckoutModel();
            $entryModel->setId($entries['id']);
            $entryModel->setDatetime($entries['date_created']);
            $entryModel->setName($entries['name']);
            $entryModel->setUsage($entries['usage']);
            $entryModel->setAmount($entries['amount']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    public function getEntryById($id)
    {
        $entry = $this->getEntries(array('id' => $id));
        return $entry;
    }

    public function getAmount()
    {
        $sql = $this->db()->selectCell('ROUND(SUM(amount),2)')
            ->from('checkout')
            ->execute();
        return $sql;
    }

    public function getAmountPlus()
    {
        $sql = 'SELECT ROUND(SUM(amount),2) FROM [prefix]_checkout
                WHERE amount > 0';
        $amount = $this->db()->queryCell($sql);
        return $amount;
    }

    public function getAmountMinus()
    {
        $sql = 'SELECT ROUND(SUM(amount),2) FROM [prefix]_checkout
                WHERE amount < 0';
        $amount = $this->db()->queryCell($sql);
        return $amount;
    }

    /**
     * Inserts or updates Checkout entry.
     *
     * @param CheckoutModel $model
     */
    public function save(CheckoutModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('checkout')
                ->fields(array('name' => $model->getName(),'date_created' => $model->getDatetime(),'usage' => $model->getUsage(),'amount' => $model->getAmount()))
                ->where(array('id' => $model->getId()))
                ->execute();
        } else {
            $this->db()->insert('checkout')
                ->fields(array('name' => $model->getName(),'date_created' => $model->getDatetime(),'usage' => $model->getUsage(),'amount' => $model->getAmount()))
                ->execute();
        }
    }

    /**
     * Deletes the Checkout entry.
     *
     * @param integer $id
     */
    public function deleteById($id)
    {
        return $this->db()->delete('checkout')
            ->where(array('id' => $id))
            ->execute();
    }
}