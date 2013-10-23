<?php
/**
 * Holds class Menu.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Models;
defined('ACCESS') or die('no direct access');

/**
 * The menu model class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Menu extends \Ilch\Model
{
    /**
     * Id of the menu.
     *
     * @var integer
     */
    protected $_id = null;

    /**
     * Content of the module.
     *
     * @var string
     */
    protected $_content = '';

    /**
     * Gets the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
    }

    /**
     * Gets the content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = (string) $content;
    }
}
