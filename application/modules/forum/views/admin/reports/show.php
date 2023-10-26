<?php

/** @var \Ilch\View $this */

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
    <div class="row mb-3">
        <label for="date" class="col-xl-2 control-label">
            <?=$this->getTrans('date') ?>:
        </label>
        <div class="col-xl-10">
            <p><?=$report->getDate() ?></p>
        </div>
    </div>
    <div class="row mb-3">
        <label for="reason" class="col-xl-2 control-label">
            <?=$this->getTrans('reason') ?>:
        </label>
        <div class="col-xl-10">
            <p><?=$this->getTrans($reasonTransKeys[$report->getReason()]) ?></p>
        </div>
    </div>
    <div class="row mb-3">
        <label for="details" class="col-xl-2 control-label">
            <?=$this->getTrans('details') ?>:
        </label>
        <div class="col-xl-10">
            <p><?=$this->escape($report->getDetails()) ?>&nbsp;</p>
        </div>
    </div>
    <div class="row mb-3">
        <label for="reporter" class="col-xl-2 control-label">
            <?=$this->getTrans('reporter') ?>:
        </label>
        <div class="col-xl-10">
            <p><?=$this->escape($report->getUsername()) ?></p>
        </div>
    </div>
<?php else : ?>
    <?=$this->getTrans('noReports') ?>
<?php endif; ?>
