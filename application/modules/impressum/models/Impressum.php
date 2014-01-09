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
     * The id of the partner.
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
     * The name of the partner.
     *
     * @var string
     */
    protected $_paragraph;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_name;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_adress;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_city;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_phone;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_disclaimer;

    /**
     * Gets the id of the partner.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the partner.
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
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getParagraph()
    {
        return $this->_paragraph;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setParagraph($paragraph)
    {
        $this->_paragraph = (string)$paragraph;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setCompany($company)
    {
        $this->_company = (string)$company;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the partner.
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
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->_adress;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setAdress($adress)
    {
        $this->_adress = (string)$adress;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setCity($city)
    {
        $this->_city = (string)$city;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setPhone($phone)
    {
        $this->_phone = (string)$phone;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getDisclaimer()
    {
        return $this->_disclaimer;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return this
     */
    public function setDisclaimer($disclaimer)
    {
        $this->_disclaimer = (string)$disclaimer;

        return $this;
    }
}
