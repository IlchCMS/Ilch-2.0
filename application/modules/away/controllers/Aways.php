<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Away\Controllers;

use Modules\Away\Mappers\Away as AwayMapper;

class Aways extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $awayMapper = new AwayMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $this->getView()->set('awayList', $awayMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')));
    }
}
