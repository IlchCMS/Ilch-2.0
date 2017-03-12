<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventsMapper;

class Events extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventsMapper = new EventsMapper();

        $this->getLayout()->setFile('modules/calendar/layouts/events');

        $this->getView()->set('eventList', $eventsMapper->getEntriesForJson($this->getRequest()->getQuery('start'), $this->getRequest()->getQuery('end')));
    }
}
