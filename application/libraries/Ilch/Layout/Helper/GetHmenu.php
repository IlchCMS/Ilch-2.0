<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;
defined('ACCESS') or die('no direct access');

class GetHmenu
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
     */
    public function getHmenu()
    {
        $model = new \Ilch\Layout\Helper\Hmenu\Model();
        return $model;
    }
}

