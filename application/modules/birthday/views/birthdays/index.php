<?php
$events = [];

// birthday entries
if ($this->get('birthdayList')) {
    $year = new Ilch\Date($this->getRequest()->getQuery('start'));
    foreach ($this->get('birthdayList') as $birthdayList) {
        if ($birthdayList->getBirthday() != '' AND $birthdayList->getBirthday() != '0000-00-00') {
            $e = [];
            $e['title'] = $this->escape($birthdayList->getName());
            $e['start'] = $year->format('Y').'-'.date('m-d', strtotime($birthdayList->getBirthday()));
            $e['allDay'] = true;
            $e['color'] = '#257e4a';
            $e['url'] = $this->getUrl('user/profil/index/user/' . $birthdayList->getId());

            $events[] = $e;
        }
    }
}

echo json_encode($events);
