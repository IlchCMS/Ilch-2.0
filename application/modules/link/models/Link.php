<?php
/**
 * Holds Contact\Models\Link.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Models;

defined('ACCESS') or die('no direct access');

/**
 * The link model class.
 *
 * @package ilch
 */
class Link extends \Ilch\Model
{
    /**
     * The id of the link.
     *
     * @var int
     */
    protected $_id;

    /**
     * The name of the link.
     *
     * @var string
     */
    protected $_name;

    /**
     * The link of the link.
     *
     * @var string
     */
    protected $_link;

    /**
     * The banner of the link.
     *
     * @var string
     */
    protected $_banner;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $_cat_id;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $_desc;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $_hits;

    /**
     * Gets the id of the link.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id of the link.
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
     * Gets the name of the link.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the link.
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
     * Gets the link of the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * Sets the link of the link.
     *
     * @param string $link
     * @return this
     */
    public function setLink($link)
    {
        $this->_link = (string)$link;

        return $this;
    }

    /**
     * Gets the banner of the link.
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->_banner;
    }

    /**
     * Sets the banner of the link.
     *
     * @param string $banner
     * @return this
     */
    public function setBanner($banner)
    {
        $this->_banner = (string)$banner;

        return $this;
    }

    /**
     * Gets the category of the link.
     *
     * @return string
     */
    public function getCatId()
    {
        return $this->_cat_id;
    }

    /**
     * Sets the category of the link.
     *
     * @param int $id
     * @return this
     */
    public function setCatId($cat)
    {
        $this->_cat_id = (int)$cat;

        return $this;
    }

    /**
     * Gets the description of the link.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Sets the description of the link.
     *
     * @param string $desc
     * @return this
     */
    public function setDesc($desc)
    {
        $this->_desc = (string)$desc;

        return $this;
    }

    /**
     * Gets the hits of the link.
     *
     * @return string
     */
    public function getHits()
    {
        return $this->_hits;
    }

    /**
     * Sets the hits of the link.
     *
     * @param string $hits
     * @return this
     */
    public function setHits($hits)
    {
        $this->_hits = (string)$hits;

        return $this;
    }
}
