<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Address extends Model
{
    /**
     * The id of the address.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The customer id.
     *
     * @var int
     */
    protected $customerID = 0;

    /**
     * The prename of the address.
     *
     * @var string
     */
    protected $prename = '';

    /**
     * The lastname of the address.
     *
     * @var string
     */
    protected $lastname = '';

    /**
     * The street of the address.
     *
     * @var string
     */
    protected $street = '';

    /**
     * The postcode of the address.
     *
     * @var string
     */
    protected $postcode = '';

    /**
     * The city of the address.
     *
     * @var string
     */
    protected $city = '';

    /**
     * The country of the address.
     *
     * @var string
     */
    protected $country = '';

    /**
     * Gets the id of the address.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the address.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Address
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the customer id.
     *
     * @return int
     */
    public function getCustomerID(): int
    {
        return $this->customerID;
    }

    /**
     * Sets the customer id.
     *
     * @param int $customerID
     * @return $this
     */
    public function setCustomerID(int $customerID): Address
    {
        $this->customerID = $customerID;
        return $this;
    }

    /**
     * Gets the prename of the address.
     *
     * @return string
     */
    public function getPrename(): string
    {
        return $this->prename;
    }

    /**
     * Sets the prename of the address.
     *
     * @param string $prename
     * @return $this
     */
    public function setPrename(string $prename): Address
    {
        $this->prename = $prename;

        return $this;
    }

    /**
     * Gets the lastname of the address.
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname of the address.
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): Address
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the street of the address.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Sets the street of the address.
     *
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): Address
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Gets the postcode of the address.
     *
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * Sets the postcode of the address.
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode(string $postcode): Address
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Gets the city of the address.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the city of the address.
     *
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): Address
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets the country of the address.
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the country of the address.
     *
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): Address
    {
        $this->country = $country;

        return $this;
    }
}
