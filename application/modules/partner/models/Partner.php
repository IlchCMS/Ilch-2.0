<?php
/**
 * Holds Contact\Models\Partner.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Models;
defined('ACCESS') or die('no direct access');

/**
 * The partner model class.
 *
 * @package ilch
 */
class Partner extends \Ilch\Model
{
    /**
     * The id of the partner.
     *
     * @var int
     */
    protected $_id;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $_name;

    /**
     * The link of the partner.
     *
     * @var string
     */
    protected $_link;

    /**
     * The banner of the partner.
     *
     * @var string
     */
    protected $_banner;
    
    /**
     * The free of the entry.
     *
     * @var integer
     */
    protected $_free;

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
     * Gets the free of the entry.
     *
     * @return integer
     */
    
    public function getFree()
    {
        return $this->_free;
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
     * Gets the link of the partner.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * Sets the link of the partner.
     *
     * @param string $email
     * @return this
     */
    public function setLink($link)
    {
        if (substr($link, 0, 7) != 'http://') {
            $link = 'http://'.$link;
        }

        $this->_link = (string)$link;

        return $this;
    }

    /**
     * Gets the banner of the partner.
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->_banner;
    }

    /**
     * Sets the banner of the partner.
     *
     * @param string $email
     * @return this
     */
    public function setBanner($banner)
    {
        if (substr($banner, 0, 7) != 'http://') {
            $banner = 'http://'.$banner;
        }

        $this->_banner = (string)$banner;

        return $this;
    }
    
    /**
     * Set the free of the entry.
     *
     * @return integer
     */
    public function setFree($free)
    {
        $this->_free = (int)$free;
    }
}
