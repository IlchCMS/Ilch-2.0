<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Base as Layout;
use Ilch\Layout\Helper\Menu\Mapper as MenuMapper;
use Ilch\Layout\Helper\Menu\Model as MenuModel;

class GetMenus
{
    /** @var Layout */
    private Layout $layout;

    /**
     * Injects the layout.
     *
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Gets all menus.
     *
     * @return MenuModel[]
     */
    public function getMenus(): array
    {
        $helperMapper = new MenuMapper($this->layout);

        return $helperMapper->getMenus();
    }
}
