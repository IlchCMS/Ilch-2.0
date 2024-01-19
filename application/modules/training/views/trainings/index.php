<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training[]|null $trainingList */
$trainingList = $this->get('trainingList');

$trainings = [];

// training entries
if ($trainingList) {
    foreach ($trainingList as $training) {
        $e = [];
        $e['title'] = $this->escape($training->getTitle());
        $e['start'] = $training->getDate();
        $e['end'] = date("Y-m-d H:i:s", strtotime('+' . $training->getTime() . ' minutes', strtotime($training->getDate())));
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('training/index/show/id/' . $training->getId());
        $trainings[] = $e;
    }
}

echo json_encode($trainings);
