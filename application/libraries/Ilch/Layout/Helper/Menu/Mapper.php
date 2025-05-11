<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;

use Ilch\Database\Mysql;
use Ilch\Layout\Base;
use Ilch\Registry;

class Mapper
{
    /**
     * @var Mysql
     */
    protected $db;

    /**
     * @var Base
     */
    protected Base $layout;

    /**
     * Injects layout and gets database.
     *
     * @param Base $layout
     */
    public function __construct(Base $layout)
    {
        $this->db = Registry::get('db');
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
        $menuRows = $this->db->select(['id', 'title'])
            ->from('menu')
            ->execute()
            ->fetchRows();

        foreach ($menuRows as $menuRow) {
            $menu = new Model($this->layout);
            $menu->setId($menuRow['id']);
            $menu->setTitle($menuRow['title']);
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
