<?php

/** @var \Ilch\View $this */

header('Content-Type: application/json');

$events = [];

/** @var \Modules\Calendar\Mappers\Calendar $calendarMapper */
$calendarMapper = $this->get('calendarMapper');

// calendar entries
/** @var \Modules\Calendar\Models\Calendar $calendar */
foreach ($this->get('calendarList') ?? [] as $calendar) {
    $e = [];
    $e['title'] = $calendar->getTitle();
    $e['start'] = $calendar->getStart();
    $e['end'] = $calendar->getEnd();
    $e['color'] = $calendar->getColor();
    $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendar->getId());

    $startDate = new \Ilch\Date($calendar->getStart());
    $endDate = $calendar->getEnd() != '1000-01-01 00:00:00'
        ? new \Ilch\Date($calendar->getEnd())
        : new \Ilch\Date('9999-12-31 23:59:59');
    $repeatUntil = $calendar->getRepeatUntil() && $calendar->getRepeatUntil() != '1000-01-01 00:00:00'
        ? new \Ilch\Date($calendar->getRepeatUntil())
        : new \Ilch\Date('9999-12-31 23:59:59');

    // Add only or initial (in case of recurring events) event.
    $events[] = $e;

    if ($calendar->getPeriodType() != '') {
        $recurrentEvents = $calendarMapper->repeat($calendar->getPeriodType(), $startDate, $endDate, $repeatUntil, $calendar->getPeriodDay());
        $iteration = 0;

        foreach ($recurrentEvents as $event) {
            $e['start'] = $event['start']->format('Y-m-d H:i:s');
            $e['end'] = $event['end']->format('Y-m-d H:i:s');
            $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendar->getId()) . '/iteration/' . $iteration;
            $events[] = $e;
            $iteration++;
        }
    }
}

echo json_encode($events);
