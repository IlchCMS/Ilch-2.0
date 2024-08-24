<?php

/** @var \Ilch\View $this */

$events = [];

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

// event entries
if ($this->get('eventList')) {
    /** @var \Modules\Events\Models\Events $event */
    foreach ($this->get('eventList') as $event) {
        if (!$adminAccess && !is_in_array($this->get('readAccess'), explode(',', $event->getReadAccess()))) {
            continue;
        }
        $e = [];
        $e['title'] = $this->escape($event->getTitle());
        $e['start'] = $event->getStart();
        $e['end'] = $event->getEnd();
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('events/show/event/id/' . $event->getId());

        $events[] = $e;
    }
}

echo json_encode($events);
