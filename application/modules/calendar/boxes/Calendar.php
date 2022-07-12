<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Boxes;

use Modules\Calendar\Mappers\Events as EventsMapper;

class Calendar extends \Ilch\Box
{
    public function render()
    {
        $eventsMapper = new EventsMapper();

        $this->getView()->set('events', $eventsMapper->getEntries());
        $this->getView()->set('uniqid', $this->getUniqid());
    }
}
