<?php
$wars = [];

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

// war entries
if ($this->get('warList')) {
    foreach ($this->get('warList') as $war) {
        if (!is_in_array($this->get('readAccess'), explode(',', $war['read_access'])) && $adminAccess == false) {
            continue;
        }

        $e = [];
        $e['title'] = $this->escape('War: '.$war['war_groups'].' vs. '.$war['war_enemy']);
        $e['start'] = $war['time'];
        $e['end'] = date("Y-m-d H:i:s", strtotime('+1 hour', strtotime($war['time'])));
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('war/index/show/id/' . $war['id']);

        $wars[] = $e;
    }
}

echo json_encode($wars);
