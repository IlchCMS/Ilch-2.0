<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Base as Layout;
use Ilch\Layout\Helper\Menu\Mapper;

class GetMenus
{
    /** @var Layout */
    private $layout;

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
     * @return \Ilch\Layout\Helper\Menu\Model[]
     */
    public function getMenus()
    {
        $helperMapper = new Mapper($this->layout);

        return $helperMapper->getMenus();
    }
}
