<?php
$calendar = $this->get('calendar');
$iteration = $this->get('iteration');
$periodDays = [
    '1' => $this->getTranslator()->trans('Monday'),
    '2' => $this->getTranslator()->trans('Tuesday'),
    '3' => $this->getTranslator()->trans('Wednesday'),
    '4' => $this->getTranslator()->trans('Thursday'),
    '5' => $this->getTranslator()->trans('Friday'),
    '6' => $this->getTranslator()->trans('Saturday'),
    '7' => $this->getTranslator()->trans('Sunday')
];
$periodTypes = [
    'daily' => $this->getTranslator()->trans('daily'),
    'weekly' => $this->getTranslator()->trans('weekly'),
    'monthly' => $this->getTranslator()->trans('monthly'),
    'quarterly' => $this->getTranslator()->trans('quarterly'),
    'days' => $this->getTranslator()->trans('days'),
];

$startDate = new \Ilch\Date($calendar->getStart());
$endDate = $calendar->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendar->getEnd()) : 1;
$repeatUntil = $calendar->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendar->getRepeatUntil()) : 1;

if ($this->get('iteration') != '') {
    $recurrence = $this->get('calendarMapper')->repeat($calendar->getPeriodType(), $startDate, $endDate, $repeatUntil, $calendar->getPeriodDay())[$iteration];
    $startDate = $recurrence['start'];
    $endDate = $recurrence['end'];
}

$endDate = is_numeric($endDate) ? null : $endDate;
?>

<h1><?=$this->escape($calendar->getTitle()) ?></h1>
<div class="form-horizontal">
    <?php if ($calendar->getPlace()): ?>
        <div class="row mb-3">
            <div class="col-xl-2"><?=$this->getTrans('place') ?></div>
            <div class="col-xl-10"><?=$this->escape($calendar->getPlace()) ?></div>
        </div>
    <?php endif; ?>
    <div class="row mb-3">
        <div class="col-xl-2"><?=$this->getTrans('start') ?></div>
        <div class="col-xl-10"><?=$this->getTrans($startDate->format('l')).$startDate->format(', d. ').$this->getTrans($startDate->format('F')).$startDate->format(' Y') ?> <?=$this->getTrans('at') ?> <?=$startDate->format('H:i') ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <?php if ($endDate): ?>
        <div class="row mb-3">
            <div class="col-xl-2"><?=$this->getTrans('end') ?></div>
            <div class="col-xl-10">
                <?=$this->getTrans($endDate->format('l')) . $endDate->format(', d. ') . $this->getTrans($endDate->format('F')) . $endDate->format(' Y') . ' ' . $this->getTrans('at').' '. $endDate->format('H:i').' '.$this->getTrans('clock') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($calendar->getPeriodType()): ?>
        <div class="row mb-3">
            <div class="col-xl-2"><?=$this->getTrans('periodEntry') ?></div>
            <div class="col-xl-10">
                <?php
                if ($calendar->getPeriodType()) {
                    echo $periodTypes[$calendar->getPeriodType()];
                    if ($calendar->getPeriodType() != 'days'){
                        echo ' (x '.$calendar->getPeriodDay().')';
                    } else {
                        echo ' ('.$periodDays[$calendar->getPeriodDay()].')';
                    }
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mb-3">
        <div class="col-xl-2"><?=$this->getTrans('description') ?></div>
        <div class="col-xl-12">
            <?php if ($calendar->getText()): ?>
                <?=$this->purify($calendar->getText()) ?>
            <?php else: ?>
                <?=$this->getTrans('noDescription') ?>
            <?php endif; ?>
        </div>
    </div>
</div>
