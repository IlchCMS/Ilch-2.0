<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Boxes;
defined('ACCESS') or die('no direct access');

class Online extends \Ilch\Box
{
    public function render()
    {
        $visitMapper = new \Modules\User\Mappers\Visit();
        
        $allCount = $visitMapper->getVisitsCountOnline();
        $users = $visitMapper->getVisitsOnlineUser();

        $this->getView()->set('usersOnline', $users);
        $this->getView()->set('guestOnline', $allCount - count($users));
    }
}

