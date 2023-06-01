<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Address as AddressModel;

class Address extends Mapper
{
    /**
     * Gets addresses.
     *
     * @param array $where
     * @return AddressModel[]|[]
     */
    public function getAddresses(array $where = []): array
    {
        $addressesArray = $this->db()->select('*')
            ->from('shop_addresses')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($addressesArray)) {
            return [];
        }

        $addresses = [];
        foreach ($addressesArray as $addressRow) {
            $addressModel = new AddressModel();
            $addressModel->setId($addressRow['id']);
            $addressModel->setCustomerID($addressRow['customerId']);
            $addressModel->setPrename($addressRow['prename']);
            $addressModel->setLastname($addressRow['lastname']);
            $addressModel->setStreet($addressRow['street']);
            $addressModel->setPostcode($addressRow['postcode']);
            $addressModel->setCity($addressRow['city']);
            $addressModel->setCountry($addressRow['country']);

            $addresses[] = $addressModel;
        }

        return $addresses;
    }

    /**
     * Gets address by id.
     *
     * @param int $id
     * @return false|AddressModel
     */
    public function getAddressById(int $id)
    {
        $order = $this->getAddresses(['id' => $id]);
        return reset($order);
    }

    /**
     * Get addresses by customer id.
     *
     * @param int $customerId
     * @return AddressModel[]
     */
    public function getAddressesByCustomerId(int $customerId): array
    {
        return $this->getAddresses(['customerId' => $customerId]);
    }

    /**
     * Inserts or updates address model.
     *
     * @param AddressModel $address
     * @return int
     */
    public function save(AddressModel $address): int
    {
        $fields = [
            'customerId' => $address->getCustomerID(),
            'prename' => $address->getPrename(),
            'lastname' => $address->getLastname(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
        ];

        if ($address->getId()) {
            $this->db()->update('shop_addresses')
                ->values($fields)
                ->where(['id' => $address->getId()])
                ->execute();
            return $address->getId();
        } else {
            return $this->db()->insert('shop_addresses')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes address with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_addresses')
            ->where(['id' => $id])
            ->execute();
    }
}
