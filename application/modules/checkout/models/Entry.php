<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Checkout\Models;

defined('ACCESS') or die('no direct access');

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var integer
     */
    protected $_id;

    /**
     * The datetime of the entry.
     *
     * @var string
     */
    protected $_datetime;

    /**
     * The name of the entry.
     *
     * @var string
     */
    protected $_name;

    /**
     * The usage of the entry.
     *
     * @var string
     */
    protected $_usage;

    /**
     * The amount of the entry.
     *
     * @var string
     */
    protected $_amount;

    /**
     * Gets the id of the entry.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->_datetime;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets the usage of the entry.
     *
     * @return string
     */
    public function getUsage()
    {
        return $this->_usage;
    }

    /**
     * Gets the amount of the entry.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Sets the id of the entry.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->_datetime = (string)$datetime;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string)$name;
    }

    /**
     * Sets the usage of the entry.
     *
     * @param string $usage
     */
    public function setUsage($usage)
    {
        $this->_usage = (string)$usage;
    }

    /**
     * Sets the amount of the entry.
     *
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->_amount = (string)$amount;
    }
}
