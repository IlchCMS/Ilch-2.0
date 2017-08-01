<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Clanlayout\Boxes;

use Modules\Clanlayout\Mappers\Layout as LayoutMapper;

class Headername extends \Ilch\Box
{
    public function render()
    {
        $layoutMapper = new LayoutMapper();

        $this->getView()->set('headername', $layoutMapper->getValueByKey('headername'));
    }
}
