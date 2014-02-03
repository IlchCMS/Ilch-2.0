<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;
defined('ACCESS') or die('no direct access');

class Mapper
{
    /**
     * Injects layout and gets database.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->_db = \Ilch\Registry::get('db');
        $this->_layout = $layout;
    }

    /**
     * Gets the menus.
     *
     * @return Ilch\Layout\Helper\Menu\Model[]
     */
    public function getMenus()
    {
        $menus = array();
        $menuRows = $this->_db->selectArray
        (
            array('id'),
            'menu'
        );

        foreach ($menuRows as $menuRow) {
            $menu = $this->getMenu($menuRow['id']);
            $menus[] = $menu;
        }

        return $menus;
    }

    /**
     * Gets the menu for the given id.
     *
     * @return Ilch\Layout\Helper\Menu\Model
     */
    public function getMenu($menuId)
    {
        $menu = new \Ilch\Layout\Helper\Menu\Model($this->_layout);

        $menuRow = $this->_db->selectRow(array('id', 'title'))
            ->from('menu')
            ->where(array('id' => $menuId))
            ->execute();

        $menu->setId($menuRow['id']);
        $menu->setTitle($menuRow['title']);

        return $menu;
    }
}
