<?php
/**
 * Holds class Category.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Models;

defined('ACCESS') or die('no direct access');

/**
 * The user category model class.
 *
 * @package ilch
 */
class Category extends \Ilch\Mapper
{
    /**
     * The id of the category.
     *
     * @var int
     */
    private $_id;

    /**
     * The name of the category.
     *
     * @var string
     */
    private $_name;

    /**
     * The catid of the category.
     *
     * @var string
     */
    private $_cat;

    /**
     * The description of the category.
     *
     * @var string
     */
    private $_desc;
    
    /**
     * The links count of the category.
     *
     * @var integer
     */
    private $_linksCount;

    /**
     * Returns the user category id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the category id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Returns the category name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the category id.
     *
     * @param string $name
     */
    public function setParentId($cat)
    {
        $this->_cat = (string) $cat;
    }

    /**
     * Returns the category id.
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->_cat;
    }

    /**
     * Sets the category name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
    }

    /**
     * Gets the description of the category.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Sets the description of the category.
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
     * Gets the links count of the category.
     *
     * @return integer
     */
    public function getLinksCount()
    {
        return $this->_linksCount;
    }

    /**
     * Sets the links count of the category.
     *
     * @param integer $count
     * @return this
     */
    public function setLinksCount($count)
    {
        $this->_linksCount = $count;

        return $this;
    }
}
