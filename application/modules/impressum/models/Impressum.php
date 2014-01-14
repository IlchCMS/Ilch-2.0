<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Impressum\Models;

defined('ACCESS') or die('no direct access');

class Impressum extends \Ilch\Model
{
    /**
     * The id of the impressum.
     *
     * @var int
     */
    protected $_id;

    /**
     * The company of the impressum.
     *
     * @var string
     */
    protected $_company;

    /**
     * The paragraph of the impressum.
     *
     * @var string
     */
    protected $_paragraph;

    /**
     * The name of the impressum.
     *
     * @var string
     */
    protected $_name;

    /**
     * The address of the impressum.
     *
     * @var string
     */
    protected $_address;

    /**
     * The city of the impressum.
     *
     * @var string
     */
    protected $_city;

    /**
     * The phone of the impressum.
     *
     * @var string
     */
    protected $_phone;

    /**
     * The disclaime of the impressum.
     *
     * @var string
     */
    protected $_disclaimer;

    /**
     * Gets the id of the impressum.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the impressum.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->_id = (int)$id;

        return $this;
    }

    /**
     * Gets the paragraph of the impressum.
     *
     * @return string
     */
    public function getParagraph()
    {
        return $this->_paragraph;
    }

    /**
     * Sets the paragraph of the impressum.
     *
     * @param string $paragraph
     * @return this
     */
    public function setParagraph($paragraph)
    {
        $this->_paragraph = (string)$paragraph;

        return $this;
    }

    /**
     * Gets the company of the impressum.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Sets the company of the impressum.
     *
     * @param string $company
     * @return this
     */
    public function setCompany($company)
    {
        $this->_company = (string)$company;

        return $this;
    }

    /**
     * Gets the name of the impressum.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the impressum.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->_name = (string)$name;

        return $this;
    }

    /**
     * Gets the address of the impressum.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * Sets the address of the impressum.
     *
     * @param string $address
     * @return this
     */
    public function setAddress($address)
    {
        $this->_address = (string)$address;

        return $this;
    }

    /**
     * Gets the city of the impressum.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * Sets the city of the impressum.
     *
     * @param string $city
     * @return this
     */
    public function setCity($city)
    {
        $this->_city = (string)$city;

        return $this;
    }

    /**
     * Gets the phone of the impressum.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Sets the phone of the impressum.
     *
     * @param string $phone
     * @return this
     */
    public function setPhone($phone)
    {
        $this->_phone = (string)$phone;

        return $this;
    }

    /**
     * Gets the disclaimer of the impressum.
     *
     * @return string
     */
    public function getDisclaimer()
    {
        return $this->_disclaimer;
    }

    /**
     * Sets the disclaimer of the impressum.
     *
     * @param string $disclaimer
     * @return this
     */
    public function setDisclaimer($disclaimer)
    {
        $this->_disclaimer = (string)$disclaimer;

        return $this;
    }
}
