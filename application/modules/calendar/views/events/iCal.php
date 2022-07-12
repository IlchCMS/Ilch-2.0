<?php
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=cal.ics');

const DATE_ICAL = 'Ymd\THis';

$ical = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Ilch 2 Kalender//Clinic Time//DE\n";

$displayedEntries = 0;

foreach ($this->get('calendarList') ?? [] as $calendarList) {
    $displayedEntries++;

    $description = $calendarList->getText();
    $description = strip_tags($description, '<p><br>');
    $description = str_replace("<p>", "", $description);
    $description = str_replace("</p>", "\\n\\n", $description);
    $description = str_replace(["<br />", "<br/>", "<br>"], "\\n", $description);
    $description = str_replace(["\r", "\n"], "", $description);
    $description = rtrim(trim($description), "\\n\\n");

    $startDate = new \Ilch\Date($calendarList->getStart());
    $endDate = $calendarList->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendarList->getEnd()) : 1;

    $days = $this->get('calendarMapper')->repeat($calendarList->getPeriodType(), $startDate, $endDate, $calendarList->getPeriodDay());
    $startDate = reset($days);
    $endDate = is_numeric($endDate) ? null : $endDate;

    $ical .=
"BEGIN:VEVENT
SUMMARY:".$calendarList->getTitle()."
UID:".$calendarList->getId()."
DTSTART:".$startDate->format(DATE_ICAL)."
DTEND:".$endDate ? $endDate->format(DATE_ICAL) : $startDate->format(DATE_ICAL)."
LOCATION:".$calendarList->getPlace()."
DESCRIPTION:".$description."
END:VEVENT\n";
}

$ical .= "END:VCALENDAR";

if ($displayedEntries) {
    echo $ical;
}

