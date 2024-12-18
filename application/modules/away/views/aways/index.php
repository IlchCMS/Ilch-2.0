<?php

/** @var \Ilch\View $this */

$events = [];

// away entries
if ($this->get('awayList')) {
    /** @var \Modules\Away\Models\Away $away */
    foreach ($this->get('awayList') as $away) {
        $e = [];
        $e['title'] = $this->escape($away->getReason());
        $e['start'] = $away->getStart().' 00:00:00';
        $e['end'] = $away->getEnd().' 23:59:59';
        if ($away->getStatus() == 0 || $away->getStatus() == 2) {
            $e['color'] = '#DF0101';
        } else {
            $e['color'] = '#04B404';
        }
        $e['url'] = $this->getUrl('away/index/index/#' . $away->getId());

        $events[] = $e;
    }
}

echo json_encode($events);
