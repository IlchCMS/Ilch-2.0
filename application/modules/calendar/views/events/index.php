<?php
$events = [];

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

// calendar entries
if ($this->get('calendarList')) {
    foreach ($this->get('calendarList') as $calendarList) {
        if (!is_in_array($this->get('readAccess'), explode(',', $calendarList->getReadAccess())) && $adminAccess == false) {
            continue;
        }
        $e = [];
        $e['title'] = $this->escape($calendarList->getTitle());
        $e['start'] = $calendarList->getStart();
        $e['end'] = $calendarList->getEnd();
        $e['color'] = $calendarList->getColor();
        $e['url'] = $this->getUrl('calendar/events/show/id/' . $calendarList->getId());

        if ($calendarList->getPeriodDay()) {
            $startDate = new \Ilch\Date($calendarList->getStart());
            $endDate = new \Ilch\Date($calendarList->getEnd());
            $e['start'] = $startDate->format('H:i');
            $e['end'] = $endDate->format('H:i');
            $e['dow'] = [$calendarList->getPeriodDay()];
        }

        array_push($events, $e);
    }
}

echo json_encode($events);
