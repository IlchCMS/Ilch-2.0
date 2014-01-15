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
     * The urlthumb of the media.
     *
     * @var string
     */
    protected $_urlthumb;

    /**
     * The ending of the media.
     *
     * @var string
     */
    protected $_ending;

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
     * Gets urlthumb of the media.
     *
     * @return string
     */
    public function getUrlThumb()
    {
        return $this->_urlthumb;
    }

    /**
     * Gets ending of the media.
     *
     * @return string
     */
    public function getEnding()
    {
        return $this->_ending;
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
     * Sets the urlthumb.
     *
     * @param string $urlthumb
     */
    public function setUrlThumb($urlthumb)
    {
        $this->_urlthumb = (string)$urlthumb;
    }

    /**
     * Sets the ending.
     *
     * @param string $ending
     */
    public function setEnding($ending)
    {
        $this->_ending = (string)$ending;
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
