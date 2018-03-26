<?php
$events = [];

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

// event entries
if ($this->get('eventList')) {
    foreach ($this->get('eventList') as $eventList) {
        if (!is_in_array($this->get('readAccess'), explode(',', $eventList->getReadAccess())) && $adminAccess == false) {
            continue;
        }
        $e = [];
        $e['title'] = $this->escape($eventList->getTitle());
        $e['start'] = $eventList->getStart();
        $e['end'] = $eventList->getEnd();
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('events/show/event/id/' . $eventList->getId());

        $events[] = $e;
    }
}

echo json_encode($events);
