<?php
$events = [];

// calendar entries
if ($this->get('calendarList')) {
    foreach ($this->get('calendarList') as $calendarList) {
        $e = [];
        $e['title'] = $this->escape($calendarList->getTitle());
        $e['start'] = $calendarList->getStart();
        $e['end'] = $calendarList->getEnd();
        $e['color'] = $calendarList->getColor();
        $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendarList->getId());

        array_push($events, $e);
    }
}

echo json_encode($events);
