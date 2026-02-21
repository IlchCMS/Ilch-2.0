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
        $e['end'] = $training->getEnd();
        $e['color'] = '#C52C66';
        $e['url'] = $this->getUrl('training/index/show/id/' . $training->getId());

        $startDate = new \Ilch\Date($calendar->getStart());
        $endDate = $calendar->getEnd() != '1000-01-01 00:00:00'
            ? new \Ilch\Date($calendar->getEnd())
            : new \Ilch\Date('9999-12-31 23:59:59');
        $repeatUntil = $calendar->getRepeatUntil() && $calendar->getRepeatUntil() != '1000-01-01 00:00:00'
            ? new \Ilch\Date($calendar->getRepeatUntil())
            : new \Ilch\Date('9999-12-31 23:59:59');

        // Add only or initial (in case of recurring events) event.
        $trainings[] = $e;

        if ($training->getPeriodType() != '') {
            $recurrentEvents = $this->get('calendarMapper')->repeat($training->getPeriodType(), $startDate, $endDate, $repeatUntil, $training->getPeriodDay());
            $iteration = 0;

            foreach ($recurrentEvents as $event) {
                $e['start'] = $event['start']->format('Y-m-d H:i:s');
                $e['end'] = $event['end']->format('Y-m-d H:i:s');
                $e['url'] = $this->getUrl('training/index/show/id/' . $training->getId()) . '/iteration/' . $iteration;
                $trainings[] = $e;
                $iteration++;
            }
        }
    }
}

echo json_encode($trainings);
