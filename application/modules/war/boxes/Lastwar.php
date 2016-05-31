<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Boxes;

use Modules\War\Mappers\War as WarMapper;
use Modules\War\Mappers\Games as GamesMapper;

class Lastwar extends \Ilch\Box
{
    public function render()
    {
        $warMapper = new WarMapper();
        $gamesMapper = new GamesMapper();

        $status = '2';
        $limit = '5';
        $war = $warMapper->getWarListByStatusAndLimt($status, $limit);

        $this->getView()->set('warMapper', $warMapper);
        $this->getView()->set('gamesMapper', $gamesMapper);
        $this->getView()->set('war', $war);
    }
}
