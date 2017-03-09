<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Updateserver extends \Ilch\Model
{
    /**
     * The id of the updateserver.
     *
     * @var int
     */
    protected $id;

    /**
     * The url of the updateserver.
     *
     * @var string
     */
    protected $url;

    /**
     * The operator of the updateserver.
     *
     * @var string
     */
    protected $operator;
    
    /**
     * The country in which the updateserver is located.
     *
     * @var string
     */
    protected $country;

    /**
     * Get the id of the updateserver.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id of the updateserver.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the url of the updateserver.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Sets the url of the updateserver.
     *
     * @param string $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the operator of the updateserver.
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Sets the operator of the updateserver.
     *
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * Gets the country in which the updateserver is located.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country in which the updateserver is located.
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
