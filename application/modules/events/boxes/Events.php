<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Boxes;

use Modules\Events\Mappers\Events as EventMapper;

class Events extends \Ilch\Box
{
    public function render()
    {
        $eventMapper = new EventMapper();
        $config = \Ilch\Registry::get('config');

        $this->getView()->set('eventList', $eventMapper->getEventListUpcoming($config->get('event_boxEventLimit')));
    }
}
