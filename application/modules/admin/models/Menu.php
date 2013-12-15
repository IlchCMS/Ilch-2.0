<?php
/**
 * Holds class Menu.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Models;
defined('ACCESS') or die('no direct access');

/**
 * The menu model class.
 *
 * @package ilch
 */
class Menu extends \Ilch\Model
{
    /**
     * Id of the menu.
     *
     * @var integer
     */
    protected $_id;
    
    /**
     * Title of the menu.
     *
     * @var string
     */
    protected $_title;

    /**
     * Sets the menu id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * Gets the menu id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = (string)$title;
    }

    /**
     * Gets the menu title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
}
