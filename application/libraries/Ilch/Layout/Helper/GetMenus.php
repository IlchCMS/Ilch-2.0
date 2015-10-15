<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;
defined('ACCESS') or die('no direct access');

class GetMenus
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
     * Gets all menus.
     *
     * @return Ilch\Layout\Helper\Menu\Model[]
     */
    public function getMenus()
    {
        $helperMapper = new \Ilch\Layout\Helper\Menu\Mapper($this->layout);

        return $helperMapper->getMenus();
    }
}
