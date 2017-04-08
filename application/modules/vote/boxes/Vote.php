<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Vote\Boxes;

use Modules\Vote\Mappers\Vote as VoteMapper;

class Vote extends \Ilch\Box
{
    public function render()
    {
        $voteMapper = new VoteMapper();

        $this->getView()->set('vote', $voteMapper->getVotes(['status' => 0]));
    }
}
