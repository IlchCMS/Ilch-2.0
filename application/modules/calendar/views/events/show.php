<?php
$calendar = $this->get('calendar');

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

$days = $this->get('calendarMapper')->repeat($calendar->getPeriodType(), $startDate, $endDate, $calendar->getPeriodDay());
$startDate = reset($days);
$endDate = is_numeric($endDate) ? null : $endDate;
?>

<h1><?=$this->escape($calendar->getTitle()) ?></h1>
<div class="form-horizontal">
    <?php if ($calendar->getPlace()): ?>
        <div class="form-group">
            <div class="col-lg-2"><?=$this->getTrans('place') ?></div>
            <div class="col-lg-10"><?=$this->escape($calendar->getPlace()) ?></div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-2"><?=$this->getTrans('start') ?></div>
        <div class="col-lg-10"><?=$this->getTrans($startDate->format("l")).$startDate->format(", d. ").$this->getTrans($startDate->format("F")).$startDate->format(" Y") ?> <?=$this->getTrans('at') ?> <?=$startDate->format("H:i") ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <?php if ($endDate): ?>
        <div class="form-group">
            <div class="col-lg-2"><?=$this->getTrans('end') ?></div>
            <div class="col-lg-10">
                <?php
                if ($startDate->format('d.m.Y') != $endDate->format('d.m.Y') && $startDate->format('H:i') != $endDate->format('H:i') && !$calendar->getPeriodType()) {
                    echo $this->getTrans($endDate->format("l")) . $endDate->format(", d. ") . $this->getTrans($endDate->format("F")) . $endDate->format(" Y") . ' ' . $this->getTrans('at').' ';
                }
                echo $endDate->format("H:i").' '.$this->getTrans('clock');
                ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($calendar->getPeriodType()): ?>
        <div class="form-group">
            <div class="col-lg-2"><?=$this->getTrans('periodEntry') ?></div>
            <div class="col-lg-10">
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
    <div class="form-group">
        <div class="col-lg-2"><?=$this->getTrans('description') ?></div>
        <div class="col-lg-12">
            <?php if ($calendar->getText()): ?>
                <?=$this->purify($calendar->getText()) ?>
            <?php else: ?>
                <?=$this->getTrans('noDescription') ?>
            <?php endif; ?>
        </div>
    </div>
</div>
