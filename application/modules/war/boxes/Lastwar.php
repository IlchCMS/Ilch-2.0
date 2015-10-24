<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Boxes;

use Modules\War\Mappers\War as WarMapper;

class Lastwar extends \Ilch\Box
{
    public function render()
    {
        $warMapper = new WarMapper();

        $status = '2';
        $limit = '5';
        $war = $warMapper->getWarListByStatusAndLimt($status, $limit);

        $this->getView()->set('war', $war);
    }
}
