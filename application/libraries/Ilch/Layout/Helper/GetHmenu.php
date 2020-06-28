<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Base as Layout;
use Ilch\Layout\Helper\Hmenu\Model;

class GetHmenu
{
    /**
     * var Ilch\Layout\Helper\Hmenu\Model
     */
    private $model;

    /**
     * Injects the layout.
     *
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->model = new Model($layout);
    }

    /**
     * Gets the hmenu
     * @return Model
     */
    public function getHmenu()
    {
        return $this->model;
    }
}
