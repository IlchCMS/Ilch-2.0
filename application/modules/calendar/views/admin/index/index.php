<?php
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
    'yearly' => $this->getTranslator()->trans('yearly'),
    'days' => $this->getTranslator()->trans('days'),
];
?>

<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($this->get('calendar') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                    <col />
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_entries') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('start') ?></th>
                        <th><?=$this->getTrans('end') ?></th>
                        <th><?=$this->getTrans('periodEntry') ?></th>
                        <th><?=$this->getTrans('repeatUntil') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('calendar') as $calendar): ?>
                    <?php
                        $startDate = new \Ilch\Date($calendar->getStart());
                        $endDate = $calendar->getEnd() != '1000-01-01 00:00:00' ? new \Ilch\Date($calendar->getEnd()) : 1;
                        $endDate = is_numeric($endDate) ? null : $endDate;
                        ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_entries', $calendar->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $calendar->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $calendar->getId()]) ?></td>
                            <td><?=$startDate->format('d.m.Y H:i') ?></td>
                            <td><?=$endDate ? $endDate->format('d.m.Y H:i') : '' ?></td>
                            <td>
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
                            </td>
                            <td><?=($calendar->getPeriodType()) ? $this->escape($calendar->getRepeatUntil()) : '' ?></td>
                            <td><?=$this->escape($calendar->getTitle()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noCalendar') ?>
<?php endif; ?>
