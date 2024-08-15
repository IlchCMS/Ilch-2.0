<?php

/** @var \Ilch\View $this */

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=cal.ics');

const DATE_ICAL = 'Ymd\THis';

$ical = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Ilch 2 Kalender//Clinic Time//DE\n";

$displayedEntries = 0;

/** @var \Modules\Calendar\Models\Calendar $calendar */
foreach ($this->get('calendarList') ?? [] as $calendar) {
    $displayedEntries++;

    $description = $calendar->getText();
    $description = strip_tags($description, '<p><br>');
    $description = str_replace('<p>', '', $description);
    $description = str_replace('</p>', "\\n\\n", $description);
    $description = str_replace(['<br />', '<br/>', '<br>'], "\\n", $description);
    $description = str_replace(["\r", "\n"], '', $description);
    $description = rtrim(trim($description), "\\n\\n");

    $startDate = new \Ilch\Date($calendar->getStart());
    $endDate = $calendar->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendar->getEnd()) : 1;
    $repeatUntil = $calendar->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendar->getRepeatUntil()) : 1;

    $endDate = is_numeric($endDate) ? null : $endDate;

    $ical .=
    'BEGIN:VEVENT
SUMMARY:' . $calendar->getTitle() . '
UID:' . $calendar->getUid() . '
DTSTART:' . date(DATE_ICAL, strtotime($calendar->getStart())) . '
DTEND:' . date(DATE_ICAL, strtotime($calendar->getEnd())) . '
LOCATION:' . $calendar->getPlace() . '
DESCRIPTION:' . $description;

    if ($calendar->getPeriodType() != '') {
        $freq = strtoupper($calendar->getPeriodType());
        $quarterlyFactor = 1;

        if ($calendar->getPeriodType() === 'quarterly') {
            // 'quarterly' doesn't exist in iCal. Translate it to every 3 months.
            $freq = 'MONTHLY';
            $quarterlyFactor = 3;
        }
        // FREQ=WEEKLY;INTERVAL=3;UNTIL=00000000T000000Z
        // Supported for FREQ: "SECONDLY" / "MINUTELY" / "HOURLY" / "DAILY" / "WEEKLY" / "MONTHLY" / "YEARLY"
        $ical .=
        "\n" . 'RRULE:FREQ=' . strtoupper($calendar->getPeriodType()) . ';INTERVAL=' . ($calendar->getPeriodDay() * $quarterlyFactor) . ';UNTIL=' . $repeatUntil->format(DATE_ICAL);
    }

    $ical .=
    "\nEND:VEVENT\n";
}

$ical .= 'END:VCALENDAR';

if ($displayedEntries) {
    echo $ical;
}
