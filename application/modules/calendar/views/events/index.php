<?php
header('Content-Type: application/json');

$events = [];

// calendar entries
foreach ($this->get('calendarList') ?? [] as $calendarList) {
    $e = [];
    $e['title'] = $this->escape($calendarList->getTitle());
    $e['start'] = $calendarList->getStart();
    $e['end'] = $calendarList->getEnd();
    $e['color'] = $calendarList->getColor();
    $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendarList->getId());

    $startDate = new \Ilch\Date($calendarList->getStart());
    $endDate = $calendarList->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendarList->getEnd()) : 1;

    $days = $this->get('calendarMapper')->repeat($calendarList->getPeriodType(), $startDate, $endDate, $calendarList->getPeriodDay());

    foreach($days as $date) {
        $e['start'] = $date->format('Y-m-d H:i:s');
        $e['end'] = '';
        if (!is_numeric($endDate)) {
            //var_dump($date->format('H') .' <= '. $endDate->format('H'));
            if ($date->format('H') <= $endDate->format('H')) {
                $e['end'] = $date->format('Y-m-d').' '. $endDate->format('H:i:s');
            }
        }
        if ($e['end'] == '') {
            $e['end'] = $e['start'];
        }

        $events[] = $e;
    }
}

echo json_encode($events);
