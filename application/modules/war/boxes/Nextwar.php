<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Boxes;

use Modules\War\Mappers\War as WarMapper;

class Nextwar extends \Ilch\Box
{
    public function render()
    {
        $warMapper = new WarMapper();        
        $date = new \Ilch\Date();
        $date = $date->format(null, true);

        $status = '1';
        $limit = '5';
        $war = $warMapper->getWarListByStatusAndLimt($status, $limit);

        $this->getView()->set('date', $date);
        $this->getView()->set('war', $war);
    }
}
