<?php
$events = [];

// birthday entries
if ($this->get('birthdayList')) {
    $year = date("Y");
    foreach ($this->get('birthdayList') as $birthdayList) {
        if ($birthdayList->getBirthday() != '' AND $birthdayList->getBirthday() != '0000-00-00') {
            $e = [];
            $e['title'] = $this->escape($birthdayList->getName());
            $e['start'] = $birthdayList->getBirthday();
            $e['allDay'] = true;
            $e['color'] = '#257e4a';
            $e['url'] = $this->getUrl('user/profil/index/user/' . $birthdayList->getId());

            array_push($events, $e);
        }
    }
}

echo json_encode($events);
