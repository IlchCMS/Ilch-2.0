<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Address;
use Modules\Shop\Models\Order as OrdersModel;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Shop\Mappers\Orderdetails as OrderdetailsMapper;

class Orders extends Mapper
{
    /**
     * Gets orders.
     *
     * @param array $where
     * @return OrdersModel[]|[]
     */
    public function getOrders(array $where = []): array
    {
        $orderdetailsMapper = new OrderdetailsMapper();

        $ordersArray = $this->db()->select()
            ->fields(['o.id', 'o.invoiceAddressId', 'o.deliveryAddressId', 'o.datetime', 'o.invoicefilename', 'o.datetimeInvoiceSent', 'o.selector', 'o.confirmCode', 'o.status'])
            ->from(['o' => 'shop_orders'])
            ->join(['c' => 'shop_customers'], 'o.customerId = c.id', 'INNER', ['customerId' => 'c.id', 'c.email'])
            ->join(['cu' => 'shop_currencies'], 'o.currencyId = cu.id', 'INNER', ['currencyId' => 'cu.id'])
            ->join(['ia' => 'shop_addresses'], 'o.invoiceAddressId = ia.id', 'INNER', ['invoiceAddressId' => 'ia.id', 'invoiceAddressCustomerId' => 'ia.customerId', 'invoiceAddressPrename' => 'ia.prename', 'invoiceAddressLastname' => 'ia.lastname', 'invoiceAddressStreet' => 'ia.street', 'invoiceAddressPostcode' => 'ia.postcode', 'invoiceAddressCity' => 'ia.city', 'invoiceAddressCountry' => 'ia.country'])
            ->join(['da' => 'shop_addresses'], 'o.deliveryAddressId = da.id', 'INNER', ['deliveryAddressId' => 'da.id', 'deliveryAddressCustomerId' => 'da.customerId', 'deliveryAddressPrename' => 'da.prename', 'deliveryAddressLastname' => 'da.lastname', 'deliveryAddressStreet' => 'da.street', 'deliveryAddressPostcode' => 'da.postcode', 'deliveryAddressCity' => 'da.city', 'deliveryAddressCountry' => 'da.country'])
            ->where($where)
            ->order(['o.status' => 'ASC', 'o.id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($ordersArray)) {
            return [];
        }

        $orderIds = [];
        foreach($ordersArray as $orderRow) {
            $orderIds[] = $orderRow['id'];
        }

        $orderdetails = $orderdetailsMapper->getOrderdetailsBy(['orderId' => $orderIds]);

        $orderdetailsAssoc = [];
        foreach($orderdetails as $orderdetail) {
            $orderdetailsAssoc[$orderdetail->getOrderId()][] = $orderdetail;
        }

        $orders = [];
        foreach ($ordersArray as $orderRow) {
            $orderModel = new OrdersModel();
            $orderModel->setId($orderRow['id']);
            $orderModel->setDatetime($orderRow['datetime']);
            $orderModel->setCurrencyId($orderRow['currencyId']);
            $orderModel->setCustomerId($orderRow['customerId']);

            $addressModel = new Address();
            $addressModel->setId($orderRow['invoiceAddressId']);
            $addressModel->setCustomerID($orderRow['invoiceAddressCustomerId']);
            $addressModel->setPrename($orderRow['invoiceAddressPrename']);
            $addressModel->setLastname($orderRow['invoiceAddressLastname']);
            $addressModel->setStreet($orderRow['invoiceAddressStreet']);
            $addressModel->setPostcode($orderRow['invoiceAddressPostcode']);
            $addressModel->setCity($orderRow['invoiceAddressCity']);
            $addressModel->setCountry($orderRow['invoiceAddressCountry']);
            $orderModel->setInvoiceAddress($addressModel);

            $addressModel = new Address();
            $addressModel->setId($orderRow['deliveryAddressId']);
            $addressModel->setCustomerID($orderRow['deliveryAddressCustomerId']);
            $addressModel->setPrename($orderRow['deliveryAddressPrename']);
            $addressModel->setLastname($orderRow['deliveryAddressLastname']);
            $addressModel->setStreet($orderRow['deliveryAddressStreet']);
            $addressModel->setPostcode($orderRow['deliveryAddressPostcode']);
            $addressModel->setCity($orderRow['deliveryAddressCity']);
            $addressModel->setCountry($orderRow['deliveryAddressCountry']);
            $orderModel->setDeliveryAddress($addressModel);

            $orderModel->setEmail($orderRow['email']);
            $orderModel->setOrderdetails($orderdetailsAssoc[$orderRow['id']]);
            $orderModel->setInvoiceFilename($orderRow['invoicefilename']);
            $orderModel->setDatetimeInvoiceSent($orderRow['datetimeInvoiceSent']);
            $orderModel->setSelector($orderRow['selector'] ?? '');
            $orderModel->setConfirmCode($orderRow['confirmCode'] ?? '');
            $orderModel->setStatus($orderRow['status']);

            $orders[] = $orderModel;
        }

        return $orders;
    }

    /**
     * Gets order by id.
     *
     * @param int $id
     * @return false|OrdersModel
     */
    public function getOrderById(int $id)
    {
        $order = $this->getOrders(['o.id' => $id]);
        return reset($order);
    }

    /**
     * Gets order by selector.
     *
     * @param string $selector
     * @return false|OrdersModel
     */
    public function getOrderBySelector(string $selector)
    {
        $order = $this->getOrders(['o.selector' => $selector]);
        return reset($order);
    }

    /**
     * Get orders by customer id. Or in other words all orders of a customer.
     *
     * @param int $customerId
     * @return OrdersModel[]
     */
    public function getOrdersByCustomerId(int $customerId): array
    {
        return $this->getOrders(['c.id' => $customerId]);
    }

    /**
     * Inserts or updates order model.
     *
     * @param OrdersModel $order
     * @return int
     */
    public function save(OrdersModel $order): int
    {
        $addressMapper = new AddressMapper();
        $orderdetailsMapper = new OrderdetailsMapper();

        $order->getInvoiceAddress()->setId($addressMapper->save($order->getInvoiceAddress()));
        $order->getDeliveryAddress()->setId($addressMapper->save($order->getDeliveryAddress()));

        $fields = [
            'datetime' => $order->getDatetime(),
            'currencyId' => $order->getCurrencyId(),
            'customerId' => $order->getCustomerId(),
            'invoiceAddressId' => $order->getInvoiceAddress()->getId(),
            'deliveryAddressId' => $order->getDeliveryAddress()->getId(),
            'invoicefilename' => $order->getInvoiceFilename(),
            'datetimeInvoiceSent' => $order->getDatetimeInvoiceSent(),
            'selector' => $order->getSelector(),
            'confirmCode' => $order->getConfirmCode(),
            'status' => $order->getStatus(),
        ];

        if ($order->getId()) {
            $id = $this->db()->update('shop_orders')
                ->values($fields)
                ->where(['id' => $order->getId()])
                ->execute();
        } else {
            $id = $this->db()->insert('shop_orders')
                ->values($fields)
                ->execute();
        }

        foreach($order->getOrderdetails() as $orderdetail) {
            $orderdetail->setOrderId($id);
        }

        $orderdetailsMapper->save($order->getOrderdetails());
        return $id;
    }

    /**
     * Inserts or updates order status.
     *
     * @param OrdersModel $order
     */
    public function updateStatus(OrdersModel $order)
    {
        $this->db()->update('shop_orders')
            ->values(['status' => $order->getStatus()])
            ->where(['id' => $order->getId()])
            ->execute();
    }

    /**
     * Deletes order with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_orders')
            ->where(['id' => $id])
            ->execute();
    }
}
