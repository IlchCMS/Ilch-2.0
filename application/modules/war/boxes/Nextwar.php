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

        $status = '1';
        $limit = '5';

        $this->getView()->set('warMapper', $warMapper);
        $this->getView()->set('date', $date->format(null, true));
        $this->getView()->set('war', $warMapper->getWarListByStatusAndLimt($status, $limit));
    }
}
