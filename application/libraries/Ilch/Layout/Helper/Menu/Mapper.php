<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;

class Mapper
{
    /**
     * @var \Ilch\Database\Mysql
     */
    protected $db;

    /**
     * @var \Ilch\Layout\Base
     */
    protected $layout;

    /**
     * Injects layout and gets database.
     *
     * @param \Ilch\Layout\Base $layout
     */
    public function __construct($layout)
    {
        $this->db = \Ilch\Registry::get('db');
        $this->layout = $layout;
    }

    /**
     * Gets the menus.
     *
     * @return \Ilch\Layout\Helper\Menu\Model[]
     */
    public function getMenus()
    {
        $menus = array();
        $menuRows = $this->db->select(array('id'))
            ->from('menu')
            ->execute()
            ->fetchRows();

        foreach ($menuRows as $menuRow) {
            $menu = $this->getMenu($menuRow['id']);
            $menus[] = $menu;
        }

        return $menus;
    }

    /**
     * Gets the menu for the given id.
     *
     * @param int $menuId
     *
     * @return \Ilch\Layout\Helper\Menu\Model
     */
    public function getMenu($menuId)
    {
        $menu = new \Ilch\Layout\Helper\Menu\Model($this->layout);

        $menuRow = $this->db->select(array('id', 'title'))
            ->from('menu')
            ->where(array('id' => $menuId))
            ->execute()
            ->fetchAssoc();

        $menu->setId($menuRow['id']);
        $menu->setTitle($menuRow['title']);

        return $menu;
    }
}
