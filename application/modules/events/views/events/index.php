<?php
$events = [];

// event entries
if ($this->get('eventList')) {
    foreach ($this->get('eventList') as $eventList) {
        $e = [];
        $e['title'] = $this->escape($eventList->getTitle());
        $e['start'] = $eventList->getStart();
        $e['end'] = $eventList->getEnd();
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('events/show/event/id/' . $eventList->getId());

        array_push($events, $e);
    }
}

echo json_encode($events);
