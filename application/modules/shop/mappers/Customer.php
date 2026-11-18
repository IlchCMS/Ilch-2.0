<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Customer as CustomerModel;
use Modules\Shop\Mappers\Orders as OrdersMapper;

class Customer extends Mapper
{
    /**
     * Gets customers.
     *
     * @param array $where
     * @return CustomerModel[]|[]
     */
    public function getCustomers(array $where = []): array
    {
        $customersArray = $this->db()->select('*')
            ->from('shop_customers')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($customersArray)) {
            return [];
        }

        $customers = [];
        foreach ($customersArray as $customerRow) {
            $customerModel = new CustomerModel();
            $customerModel->setId($customerRow['id']);
            $customerModel->setUserId($customerRow['userId']);
            $customerModel->setEmail($customerRow['email']);

            $customers[] = $customerModel;
        }

        return $customers;
    }

    /**
     * Gets customer by id.
     *
     * @param int $id
     * @return false|CustomerModel
     */
    public function getCustomerById(int $id): bool|CustomerModel
    {
        $customer = $this->getCustomers(['id' => $id]);
        return reset($customer);
    }

    /**
     * Get customer by user id.
     *
     * @param int $userId
     * @return false|CustomerModel
     */
    public function getCustomerByUserId(int $userId): bool|CustomerModel
    {
        $customer = $this->getCustomers(['userId' => $userId]);
        return reset($customer);
    }

    /**
     * Inserts or updates customer model.
     *
     * @param CustomerModel $customer
     * @return int ID of the saved customer.
     */
    public function save(CustomerModel $customer): int
    {
        $fields = [
            'userId' => $customer->getUserId(),
            'email' => $customer->getEmail(),
        ];

        if ($customer->getId()) {
            $this->db()->update('shop_customers')
                ->values($fields)
                ->where(['id' => $customer->getId()])
                ->execute();

            return $customer->getId();
        }

        return $this->db()->insert('shop_customers')
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes customer with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) $this->db()->delete('shop_customers')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Completely deletes the customer: the customer row (addresses,
     * orders and order details cascade via foreign keys) plus the invoice
     * PDF files of all orders that are no longer subject to the statutory
     * retention period.
     *
     * If any of the customer's orders still has an invoice within the
     * retention period, nothing is deleted.
     *
     * @param int $id
     * @return bool true if the customer was deleted, false otherwise
     */
    public function deleteComplete(int $id, int $retentionYears): bool
    {
        $ordersMapper = new OrdersMapper();

        foreach ($ordersMapper->getOrdersByCustomerId($id) as $order) {
            // A retention document still exists → refuse, touch nothing.
            if ($ordersMapper->isWithinInvoiceRetentionPeriod($order, $retentionYears)) {
                return false;
            }

            // Invoices whose retention period has expired (and orders
            // without an invoice) lose their PDF file at this point.
            $ordersMapper->deleteInvoiceFile($order);
        }

        return $this->delete($id);
    }
}
