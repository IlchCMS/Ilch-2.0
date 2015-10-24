<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var integer
     */
    protected $id;

    /**
     * The datetime of the entry.
     *
     * @var string
     */
    protected $datetime;

    /**
     * The name of the entry.
     *
     * @var string
     */
    protected $name;

    /**
     * The usage of the entry.
     *
     * @var string
     */
    protected $usage;

    /**
     * The amount of the entry.
     *
     * @var string
     */
    protected $amount;

    /**
     * Gets the id of the entry.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the usage of the entry.
     *
     * @return string
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * Gets the amount of the entry.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the id of the entry.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = (string)$datetime;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Sets the usage of the entry.
     *
     * @param string $usage
     */
    public function setUsage($usage)
    {
        $this->usage = (string)$usage;
    }

    /**
     * Sets the amount of the entry.
     *
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = (string)$amount;
    }
}
