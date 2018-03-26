<?php
$events = [];

// away entries
if ($this->get('awayList')) {
    foreach ($this->get('awayList') as $awayList) {
        $e = [];
        $e['title'] = $this->escape($awayList->getReason());
        $e['start'] = $awayList->getStart().' 00:00:00';
        $e['end'] = $awayList->getEnd().' 23:59:59';
        if ($awayList->getStatus() == 0 OR $awayList->getStatus() == 2) {
            $e['color'] = '#DF0101';
        } else {
            $e['color'] = '#04B404';
        }
        $e['url'] = $this->getUrl('away/index/index/#' . $awayList->getId());

        $events[] = $e;
    }
}

echo json_encode($events);
