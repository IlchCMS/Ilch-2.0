<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Birthday\Boxes;

use Ilch\Box;
use Ilch\Date;
use Modules\Birthday\Mappers\Birthday as BirthdayMapper;

class Birthday extends Box
{
    public function render()
    {
        $date = new Date();
        $birthdayMapper = new BirthdayMapper();

        if ($this->getUser() || $this->getConfig()->get('bday_visibleForGuest')) {
            $this->getView()->set('birthdayDateNowYMD', $date->format('Ymd'));
            $this->getView()->set('birthdayListNOW', $birthdayMapper->getBirthdayUserList($this->getConfig()->get('bday_boxShow')));
        }
    }
}
