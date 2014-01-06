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
    private $_name = '';

    /**
     * The catid of the category.
     *
     * @var string
     */
    private $_cat = '';

    /**
     * The description of the category.
     *
     * @var string
     */
    private $_desc = '';

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
    public function setCatId($cat)
    {
        $this->_cat = (string) $cat;
    }

    /**
     * Returns the category id.
     *
     * @return string
     */
    public function getCatId()
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
}
