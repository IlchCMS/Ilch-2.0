<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;
defined('ACCESS') or die('no direct access');

class GetMenu
{
    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->_layout = $layout;
    }

    /**
     * Gets the menu for the given position.
     *
     * @param integer $menu
     * @return Ilch\Layout\Helper\Menu\Model
     */
    public function getMenu($menu = 1)
    {
        $helperMapper = new \Ilch\Layout\Helper\Menu\Mapper($this->_layout);
        $menuMapper = new \Admin\Mappers\Menu();

        return $helperMapper->getMenu($menuMapper->getMenuIdForPosition($menu));
    }
}

