<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Birthday\Controllers;

use Modules\Birthday\Mappers\Birthday as BirthdayMapper;

class Birthdays extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $birthdayMapper = new BirthdayMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $this->getView()->set('birthdayList', $birthdayMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')));
    }
}
