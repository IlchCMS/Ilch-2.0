<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Online;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $visitMapper = new \User\Mappers\Visit();
        
        $allCount = $visitMapper->getVisitsCountOnline();
        $userCount = $visitMapper->getVisitsCountOnline(true);

        $this->getView()->set('userOnline', $userCount);
        $this->getView()->set('guestOnline', $allCount - $userCount);
    }
}

