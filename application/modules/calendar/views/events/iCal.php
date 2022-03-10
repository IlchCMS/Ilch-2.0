<?php
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=cal.ics');

define('DATE_ICAL', 'Ymd\THis');

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

if ($this->get('calendarList')) {
    $ical = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Ilch 2.0 Kalender//Clinic Time//DE\n";

    $displayedEntries = 0;

    foreach ($this->get('calendarList') as $calendarList) {
        if (!is_in_array($this->get('readAccess'), explode(',', $calendarList->getReadAccess())) && $adminAccess == false) {
            continue;
        }
        $displayedEntries++;

        $description = $calendarList->getText();
        $description = strip_tags($description, '<p><br>');
        $description = str_replace("<p>", "", $description);
        $description = str_replace("</p>", "\\n\\n", $description);
        $description = str_replace(["<br />", "<br/>", "<br>"], "\\n", $description);
        $description = str_replace(["\r", "\n"], "", $description);
        $description = rtrim(trim($description), "\\n\\n");

        $ical .=
"BEGIN:VEVENT
SUMMARY:".$calendarList->getTitle()."
UID:".$calendarList->getId()."
DTSTART:".date(DATE_ICAL, strtotime($calendarList->getStart()))."
DTEND:".date(DATE_ICAL, strtotime($calendarList->getEnd()))."
LOCATION:".$calendarList->getPlace()."
DESCRIPTION:".$description."
END:VEVENT\n";
    }

    $ical .= "END:VCALENDAR";

    if ($displayedEntries) {
        echo $ical;
    }
}
