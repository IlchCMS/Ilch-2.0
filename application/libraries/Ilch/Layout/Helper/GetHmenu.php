<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetHmenu
{
    /**
     * var Ilch\Layout\Helper\Hmenu\Model
     */
    private $model;

    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->model = new \Ilch\Layout\Helper\Hmenu\Model($layout);
    }

    /**
     * Gets the hmenu
     * @return \Ilch\Layout\Helper\Hmenu\Model
     */
    public function getHmenu()
    {
        return $this->model;
    }
}

