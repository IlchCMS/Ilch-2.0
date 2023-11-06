<?php
header('Content-Type: application/json');

$events = [];

// calendar entries
foreach ($this->get('calendarList') ?? [] as $calendarList) {
    $e = [];
    $e['title'] = $calendarList->getTitle();
    $e['start'] = $calendarList->getStart();
    $e['end'] = $calendarList->getEnd();
    $e['color'] = $calendarList->getColor();
    $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendarList->getId());

    $startDate = new \Ilch\Date($calendarList->getStart());
    $endDate = $calendarList->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendarList->getEnd()) : 1;
    $repeatUntil = $calendarList->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendarList->getRepeatUntil()) : 1;

    // Add only or initial (in case of recurring events) event.
    $events[] = $e;

    if ($calendarList->getPeriodType() != '') {
        $recurrentEvents = $this->get('calendarMapper')->repeat($calendarList->getPeriodType(), $startDate, $endDate, $repeatUntil, $calendarList->getPeriodDay());
        $iteration = 0;

        foreach($recurrentEvents as $event) {
            $e['start'] = $event['start']->format('Y-m-d H:i:s');
            $e['end'] = $event['end']->format('Y-m-d H:i:s');
            $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendarList->getId()) . '/iteration/' . $iteration;
            $events[] = $e;
            $iteration++;
        }
    }
}

echo json_encode($events);
