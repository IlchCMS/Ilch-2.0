<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetAdminHmenu
{
    /**
     * var Ilch\Layout\Helper\AdminHmenu\Model
     */
    private $model;

    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->model = new \Ilch\Layout\Helper\AdminHmenu\Model($layout);
    }

    /**
     * Gets the hmenu
     * @return \Ilch\Layout\Helper\AdminHmenu\Model
     */
    public function getAdminHmenu()
    {
        return $this->model;
    }
}

