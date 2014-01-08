<?php
/**
 * Holds Media Model.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Models;
defined('ACCESS') or die('no direct access');

class Media extends \Ilch\Model 
{
    /**
     * The id of the media.
     *
     * @var int
     */
    protected $_id;

    /**
     * The url of the media.
     *
     * @var string
     */
    protected $_url;

    /**
     * The description of the media.
     *
     * @var string
     */
    protected $_description;

    /**
     * The name of the media.
     *
     * @var string
     */
    protected $_name;

    /**
     * The datetime of the media.
     *
     * @var string
     */
    protected $_datetime;

    /**
     * Gets the id of the media.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Gets url of the media.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Gets description of the media.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Gets name of the media.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets datetime of the media.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->_datetime;
    }

    /**
     * Sets the id of the media.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * Sets the url.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * Sets the description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = (string)$description;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string)$name;
    }

    /**
     * Sets the datetime.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->_datetime = (string)$datetime;
    }
}
