<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetMenu
{
    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Gets the menu for the given position.
     *
     * @param integer $menu
     * @param string $html
     * @return string
     */
    public function getMenu($args)
    {
        $menuId = $args[0];

        $helperMapper = new \Ilch\Layout\Helper\Menu\Mapper($this->layout);
        $menuMapper = new \Modules\Admin\Mappers\Menu();

        $menu = $helperMapper->getMenu($menuMapper->getMenuIdForPosition($menuId));

        if (isset($args[1]) && isset($args[2])) {
            return $menu->getItems($args[1], $args[2]);
        }

        if (isset($args[1])) {
            return $menu->getItems($args[1]);
        }

        return $menu->getItems();
    }
}

