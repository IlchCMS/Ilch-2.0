<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Boxes;

use Modules\Events\Mappers\Events as EventMapper;

defined('ACCESS') or die('no direct access');

class Birthday extends \Ilch\Box
{
    public function render()
    {
        $date = new \Ilch\Date();
        $birthdayMapper = new EventMapper();
        
        $this->getView()->set('birthdayDateNowYMD', $date->format('Ymd'));
        $this->getView()->set('birthdayListNOW', $birthdayMapper->getBirthdayUserListNOW());
    }
}
