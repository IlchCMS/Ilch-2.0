<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Birthday\Controllers;

use Ilch\Validation;
use Modules\Birthday\Mappers\Birthday as BirthdayMapper;

class Birthdays extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $birthdayMapper = new BirthdayMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $birthdayList = [];

        if ($this->getUser() || $this->getConfig()->get('bday_visibleForGuest')) {
            $input = $this->getRequest()->getQuery();
            $validation = Validation::create(
                $input, [
                    'start' => 'required|date',
                    'end'   => 'required|date',
                ]
            );

            if ($validation->isValid()) {
                $birthdayList = $birthdayMapper->getEntriesForJson($input['start'], $input['end']);
            }
        }

        $this->getView()->set('birthdayList', $birthdayList);
    }
}
