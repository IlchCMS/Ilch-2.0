<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Base as Layout;

class GetTitle
{
    /**
     * var Ilch\Layout\Helper\Title\Model
     */
    private $model;

    /**
     * Injects the title.
     *
     * @param Layout $title
     */
    public function __construct(Layout $title)
    {
        $this->model = new \Ilch\Layout\Helper\Title\Model();
    }

    /**
     * Gets the title
     * @return \Ilch\Layout\Helper\Title\Model
     */
    public function getTitle(): Title\Model
    {
        return $this->model;
    }
}
