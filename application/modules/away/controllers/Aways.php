<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Controllers;

use Ilch\Validation;
use Modules\Away\Mappers\Away as AwayMapper;

class Aways extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $awayMapper = new AwayMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $awayList = [];

        $input = $this->getRequest()->getQuery();
        $validation = Validation::create(
            $input,
            [
                'start' => 'required|date:Y-m-d\TH\\:i\\:sP',
                'end'   => 'required|date:Y-m-d\TH\\:i\\:sP',
            ]
        );

        if ($validation->isValid()) {
            $awayList = $awayMapper->getEntriesForJson($input['start'], $input['end']);
        }

        $this->getView()->set('awayList', $awayList);
    }
}
