<?php
$trainings = [];

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

// training entries
if ($this->get('trainingList')) {
    foreach ($this->get('trainingList') as $training) {
        if (!is_in_array($this->get('readAccess'), explode(',', $training->getReadAccess())) && $adminAccess == false) {
            continue;
        }

        $e = [];
        $e['title'] = $this->escape($training->getTitle());
        $e['start'] = $training->getDate();
        $e['end'] = date("Y-m-d H:i:s", strtotime('+'.$training->getTime().' minutes', strtotime($training->getDate())));
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('training/index/show/id/' . $training->getId());

        $trainings[] = $e;
    }
}

echo json_encode($trainings);
