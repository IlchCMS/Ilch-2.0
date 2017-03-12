<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Calendar\Controllers;

use Modules\Calendar\Mappers\Events as EventsMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventsMapper = new EventsMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuCalendar'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuCalendar'), ['controller' => 'index']);

        $this->getView()->set('events', $eventsMapper->getEntries());
    }
}
