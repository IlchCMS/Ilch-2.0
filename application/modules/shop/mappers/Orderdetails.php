<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;

/**
 * Mapper for the details for the items that are part of an order at time of the order.
 */
class Orderdetails extends Mapper
{
    /**
     * Get orderdetails
     *
     * @param array $where
     * @return array
     */
    public function getOrderdetailsBy(array $where = []): array
    {
        $orderdetailsArray = $this->db()->select('*')
            ->from('shop_orderdetails')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($orderdetailsArray)) {
            return [];
        }

        $orderdetails = [];
        foreach ($orderdetailsArray as $orderdetailRow) {
            $orderdetailsModel = new OrderdetailsModel();
            $orderdetailsModel->setId($orderdetailRow['id']);
            $orderdetailsModel->setOrderId($orderdetailRow['orderId']);
            $orderdetailsModel->setItemId($orderdetailRow['itemId']);
            $orderdetailsModel->setPrice($orderdetailRow['price']);
            $orderdetailsModel->setQuantity($orderdetailRow['quantity']);
            $orderdetailsModel->setTax($orderdetailRow['tax']);
            $orderdetailsModel->setShippingCosts($orderdetailRow['shippingCosts']);

            $orderdetails[] = $orderdetailsModel;
        }

        return $orderdetails;
    }

    /**
     * Get orderdetails by order id.
     * This are typically details for all items of an order.
     *
     * @param int $orderId
     * @return array
     */
    public function getOrderdetailsByOrderId(int $orderId): array
    {
        return $this->getOrderdetailsBy(['orderId' => $orderId]);
    }

    /**
     * Get orderdetails by id.
     * This is just one item and isn't necessarily all items of an order.
     *
     * @param int $id
     * @return false|OrderdetailsModel
     */
    public function getOrderdetailsById(int $id)
    {
        $order = $this->getOrderdetailsBy(['id' => $id]);
        return reset($order);
    }

    /**
     * Inserts orderdetails.
     * Updating orderdetails is not supported as changing e.g. the price of an article after
     * an order is not desired (at least for the customer).
     *
     * @param OrderdetailsModel[] $orderdetailsArray
     * @return int number of affected rows
     */
    public function save(array $orderdetailsArray): int
    {
        $fields = [];

        foreach ($orderdetailsArray as $orderdetails) {
            if ($orderdetails->getId()) {
                return 0;
            }

            $fields[] = [
                'orderId' => $orderdetails->getOrderId(),
                'itemId' => $orderdetails->getItemId(),
                'price' => $orderdetails->getPrice(),
                'quantity' => $orderdetails->getQuantity(),
                'tax' => $orderdetails->getTax(),
                'shippingCosts' => $orderdetails->getShippingCosts(),
            ];
        }

        return $this->db()->insert('shop_orderdetails')
            ->columns(['orderId', 'itemId', 'price', 'quantity', 'tax', 'shippingCosts'])
            ->values($fields)
            ->execute();
    }
}
