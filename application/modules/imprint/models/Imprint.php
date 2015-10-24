<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Models;

class Imprint extends \Ilch\Model
{
    /**
     * The id of the imprint.
     *
     * @var int
     */
    protected $id;

    /**
     * The company of the imprint.
     *
     * @var string
     */
    protected $company;

    /**
     * The paragraph of the imprint.
     *
     * @var string
     */
    protected $paragraph;

    /**
     * The name of the imprint.
     *
     * @var string
     */
    protected $name;

    /**
     * The address of the imprint.
     *
     * @var string
     */
    protected $address;

    /**
     * The additional address of the imprint.
     *
     * @var string
     */
    protected $addressadd;

    /**
     * The city of the imprint.
     *
     * @var string
     */
    protected $city;

    /**
     * The phone of the imprint.
     *
     * @var string
     */
    protected $phone;

    /**
     * The fax of the imprint.
     *
     * @var string
     */
    protected $fax;

    /**
     * The email of the imprint.
     *
     * @var string
     */
    protected $email;

    /**
     * The registration of the imprint.
     *
     * @var string
     */
    protected $registration;

    /**
     * The commercialregister of the imprint.
     *
     * @var string
     */
    protected $commercialregister;

    /**
     * The vatid of the imprint.
     *
     * @var string
     */
    protected $vatid;

    /**
     * The other of the imprint.
     *
     * @var string
     */
    protected $other;

    /**
     * The disclaime of the imprint.
     *
     * @var string
     */
    protected $disclaimer;

    /**
     * Gets the id of the imprint.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the imprint.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the paragraph of the imprint.
     *
     * @return string
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Sets the paragraph of the imprint.
     *
     * @param string $paragraph
     * @return this
     */
    public function setParagraph($paragraph)
    {
        $this->paragraph = (string)$paragraph;

        return $this;
    }

    /**
     * Gets the company of the imprint.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company of the imprint.
     *
     * @param string $company
     * @return this
     */
    public function setCompany($company)
    {
        $this->company = (string)$company;

        return $this;
    }

    /**
     * Gets the name of the imprint.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the imprint.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the address of the imprint.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address of the imprint.
     *
     * @param string $address
     * @return this
     */
    public function setAddress($address)
    {
        $this->address = (string)$address;

        return $this;
    }

    /**
     * Gets the additional address of the imprint.
     *
     * @return string
     */
    public function getAddressAdd()
    {
        return $this->addressadd;
    }

    /**
     * Sets the additional address of the imprint.
     *
     * @param string $addressadd
     * @return this
     */
    public function setAddressAdd($addressadd)
    {
        $this->addressadd = (string)$addressadd;

        return $this;
    }

    /**
     * Gets the city of the imprint.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city of the imprint.
     *
     * @param string $city
     * @return this
     */
    public function setCity($city)
    {
        $this->city = (string)$city;

        return $this;
    }

    /**
     * Gets the phone of the imprint.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone of the imprint.
     *
     * @param string $phone
     * @return this
     */
    public function setPhone($phone)
    {
        $this->phone = (string)$phone;

        return $this;
    }

    /**
     * Gets the fax of the imprint.
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets the fax of the imprint.
     *
     * @param string $fax
     * @return this
     */
    public function setFax($fax)
    {
        $this->fax = (string)$fax;

        return $this;
    }

    /**
     * Gets the email of the imprint.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the imprint.
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Gets the registration of the imprint.
     *
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Sets the registration of the imprint.
     *
     * @param string $registration
     * @return this
     */
    public function setRegistration($registration)
    {
        $this->registration = (string)$registration;

        return $this;
    }

    /**
     * Gets the commercialregister of the imprint.
     *
     * @return string
     */
    public function getCommercialRegister()
    {
        return $this->commercialregister;
    }

    /**
     * Sets the commercialregister of the imprint.
     *
     * @param string $commercialregister
     * @return this
     */
    public function setCommercialRegister($commercialregister)
    {
        $this->commercialregister = (string)$commercialregister;

        return $this;
    }

    /**
     * Gets the vatid of the imprint.
     *
     * @return string
     */
    public function getVatId()
    {
        return $this->vatid;
    }

    /**
     * Sets the vatid of the imprint.
     *
     * @param string $vatid
     * @return this
     */
    public function setVatId($vatid)
    {
        $this->vatid = (string)$vatid;

        return $this;
    }

    /**
     * Gets the other of the imprint.
     *
     * @return string
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets the other of the imprint.
     *
     * @param string $other
     * @return this
     */
    public function setOther($other)
    {
        $this->other = (string)$other;

        return $this;
    }

    /**
     * Gets the disclaimer of the imprint.
     *
     * @return string
     */
    public function getDisclaimer()
    {
        return $this->disclaimer;
    }

    /**
     * Sets the disclaimer of the imprint.
     *
     * @param string $disclaimer
     * @return this
     */
    public function setDisclaimer($disclaimer)
    {
        $this->disclaimer = (string)$disclaimer;

        return $this;
    }
}
