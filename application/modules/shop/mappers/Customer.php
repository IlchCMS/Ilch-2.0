<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Customer as CustomerModel;

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
    public function getCustomerById(int $id)
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
    public function getCustomerByUserId(int $userId)
    {
        $customer = $this->getCustomers(['userId' => $userId]);
        return reset($customer);
    }

    /**
     * Inserts or updates customer model.
     *
     * @param CustomerModel $customer
     * @return int
     */
    public function save(CustomerModel $customer): int
    {
        $fields = [
            'userId' => $customer->getUserId(),
            'email' => $customer->getEmail(),
        ];

        if ($customer->getId()) {
            return $this->db()->update('shop_customers')
                ->values($fields)
                ->where(['id' => $customer->getId()])
                ->execute();
        } else {
            return $this->db()->insert('shop_customers')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes customer with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_customers')
            ->where(['id' => $id])
            ->execute();
    }
}
