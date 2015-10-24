<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Birthday\Boxes;

use Modules\Birthday\Mappers\Birthday as BirthdayMapper;

class Birthday extends \Ilch\Box
{
    public function render()
    {
        $date = new \Ilch\Date();
        $birthdayMapper = new BirthdayMapper();

        $this->getView()->set('birthdayDateNowYMD', $date->format('Ymd'));
        $this->getView()->set('birthdayListNOW', $birthdayMapper->getBirthdayUserList($this->getConfig()->get('bday_boxShow')));
    }
}
