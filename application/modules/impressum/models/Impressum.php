<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Impressum\Models;

defined('ACCESS') or die('no direct access');

class Impressum extends \Ilch\Model
{
    /**
     * The id of the impressum.
     *
     * @var int
     */
    protected $id;

    /**
     * The company of the impressum.
     *
     * @var string
     */
    protected $company;

    /**
     * The paragraph of the impressum.
     *
     * @var string
     */
    protected $paragraph;

    /**
     * The name of the impressum.
     *
     * @var string
     */
    protected $name;

    /**
     * The address of the impressum.
     *
     * @var string
     */
    protected $address;

    /**
     * The city of the impressum.
     *
     * @var string
     */
    protected $city;

    /**
     * The phone of the impressum.
     *
     * @var string
     */
    protected $phone;

    /**
     * The disclaime of the impressum.
     *
     * @var string
     */
    protected $disclaimer;

    /**
     * Gets the id of the impressum.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the impressum.
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
     * Gets the paragraph of the impressum.
     *
     * @return string
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Sets the paragraph of the impressum.
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
     * Gets the company of the impressum.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company of the impressum.
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
     * Gets the name of the impressum.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the impressum.
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
     * Gets the address of the impressum.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address of the impressum.
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
     * Gets the city of the impressum.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city of the impressum.
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
     * Gets the phone of the impressum.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone of the impressum.
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
     * Gets the disclaimer of the impressum.
     *
     * @return string
     */
    public function getDisclaimer()
    {
        return $this->disclaimer;
    }

    /**
     * Sets the disclaimer of the impressum.
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
