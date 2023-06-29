<?php

/**
 * @copyright Ilch 2
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
    public function __construct(\Ilch\Layout\Base $layout)
    {
        $this->db = \Ilch\Registry::get('db');
        $this->layout = $layout;
    }

    /**
     * Gets the menus.
     *
     * @return Model[]
     */
    public function getMenus(): array
    {
        $menus = [];
        $menuRows = $this->db->select(['id'])
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
     * @return Model
     */
    public function getMenu(int $menuId): Model
    {
        $menu = new Model($this->layout);

        $menuRow = $this->db->select(['id', 'title'])
            ->from('menu')
            ->where(['id' => $menuId])
            ->execute()
            ->fetchAssoc();

        if (!empty($menuRow)) {
            $menu->setId($menuRow['id']);
            $menu->setTitle($menuRow['title']);
        }

        return $menu;
    }
}
