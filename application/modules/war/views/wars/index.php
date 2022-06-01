<?php
$wars = [];

// war entries
if ($this->get('warList')) {
    foreach ($this->get('warList') as $war) {
        $e = [];
        $e['title'] = $this->escape('War: '.$war->getWarGroupTag().' vs. '.$war->getWarEnemyTag());
        $e['start'] = $war->getWarTime();
        $e['end'] = date("Y-m-d H:i:s", strtotime('+1 hour', strtotime($war->getWarTime())));
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('war/index/show/id/' . $war->getId());

        $wars[] = $e;
    }
}

echo json_encode($wars);
