<?php

/** @var \Ilch\View $this */

use Ilch\Date;

$reasonTransKeys = [
    '1' => 'illegalContent',
    '2' => 'spam',
    '3' => 'wrongTopic',
    '4' => 'other',
];
/** @var \Modules\Forum\Models\Report|null $report */
$report = $this->get('report');
?>
<h1><?=$this->getTrans('report') ?></h1>
<?php if (!empty($report)) : ?>
    <div class="form-group">
        <label for="date" class="col-lg-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-lg-10">
            <?php $date = new Date($report->getDate()); ?>
            <p><?=$date->format('d.m.y - H:i', true) ?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="reason" class="col-lg-2 control-label">
            <?=$this->getTrans('reason') ?>:
        </label>
        <div class="col-lg-10">
            <p><?=$this->getTrans($reasonTransKeys[$report->getReason()]) ?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="details" class="col-lg-2 control-label">
            <?=$this->getTrans('details') ?>:
        </label>
        <div class="col-lg-10">
            <p><?=$this->escape($report->getDetails()) ?>&nbsp;</p>
        </div>
    </div>
    <div class="form-group">
        <label for="reporter" class="col-lg-2 control-label">
            <?=$this->getTrans('reporter') ?>:
        </label>
        <div class="col-lg-10">
            <p><?=$this->escape($report->getUsername()) ?></p>
        </div>
    </div>
<?php else : ?>
    <?=$this->getTrans('noReports') ?>
<?php endif; ?>
