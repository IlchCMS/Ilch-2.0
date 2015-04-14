<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Boxes;

use Modules\Events\Mappers\Events as EventMapper;

defined('ACCESS') or die('no direct access');

class Events extends \Ilch\Box
{
    public function render()
    {
        $date = new \Ilch\Date();
        $eventMapper = new EventMapper();

        $this->getView()->set('eventList', $eventMapper->getEventListUpcoming(5));
    }
}
