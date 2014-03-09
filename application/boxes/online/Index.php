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
        $userMapper = new \User\Mappers\User();
        
        $allCount = $userMapper->getVisitsCountOnline();
        $userCount = $userMapper->getVisitsCountOnline(true);

        $this->getView()->set('userOnline', $userCount);
        $this->getView()->set('guestOnline', $allCount - $userCount);
    }
}

