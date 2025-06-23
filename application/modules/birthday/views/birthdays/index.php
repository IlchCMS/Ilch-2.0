<?php

/** @var \Ilch\View $this */

$events = [];

// birthday entries
if ($this->get('birthdayList')) {
    $year = new Ilch\Date($this->getRequest()->getQuery('start'));
    /** @var null|Modules\User\Models\User $user */
    foreach ($this->get('birthdayList') as $user) {
        if ($user->getBirthday() != '' && $user->getBirthday() != '0000-00-00') {
            $e = [];
            $e['title'] = $this->escape($user->getName());
            $e['start'] = $year->format('Y').'-'.date('m-d', strtotime($user->getBirthday()));
            $e['allDay'] = true;
            $e['color'] = '#257e4a';
            $e['url'] = $this->getUrl('user/profil/index/user/' . $user->getId());

            $events[] = $e;
        }
    }
}

echo json_encode($events);
