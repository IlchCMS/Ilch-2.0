<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Models;
defined('ACCESS') or die('no direct access');

/**
 * The layout model class.
 *
 * @package ilch
 */
class Layout extends \Ilch\Model
{
    /**
     * Key of the layout.
     *
     * @var string
     */
    protected $_key;

    /**
     * Author of the layout.
     *
     * @var string
     */
    protected $_author;

    /**
     * Description of the layout.
     *
     * @var string
     */
    protected $_desc;

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->_key = (string)$key;
    }

    /**
     * Gets the author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->_author = (string)$author;
    }
    
    /**
     * Gets the desc.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Sets the author.
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->_desc = (string)$desc;
    }
}
