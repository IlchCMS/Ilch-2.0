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
     * Mapper of the menu.
     *
     * @var \Ilch\Mapper
     */
    protected $_mapper;

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
     * Injects mapper to model.
     * 
     * @todo it is a workaround for the rec function.
     * @param \Ilch\Mapper $mapper
     */
    public function __construct($mapper = '')
    {
        $this->_mapper = $mapper;
    }

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

    /**
     * Gets the menu items as html-string.
     * 
     * @return string
     */
    public function getItems()
    {
        $html = '';

        foreach ($this->_mapper->getMenuItemsByParent($this->getId(), 0) as $item) {
            $html .= $this->_rec($item);
        }

        return $html;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param \Admin\Models\MenuItem $item
     * @return string
     */
    protected function _rec($item)
    {
        $subItems = $this->_mapper->getMenuItemsByParent(1, $item->getId());

        $html = '<ul><li>';
        $html .= $item->getTitle();

        if (!empty($subItems)) {
            $html .= '<ul>';

            foreach ($subItems as $subItem) {
                $html .= $this->_rec($subItem);
            }

            $html .= '</ul>';
        }

        return $html .= '</li></ul>';
    }
}
