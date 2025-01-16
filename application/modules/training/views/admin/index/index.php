<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training[]|null $trainings */
$trainings = $this->get('trainings');

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
<?php if ($trainings) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                    <col>
                    <col class="col-xl-2">
                    <col class="col-xl-2">
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_trainings') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('start') ?></th>
                        <th><?=$this->getTrans('end') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                        <th><?=$this->getTrans('periodEntry') ?></th>
                        <th><?=$this->getTrans('repeatUntil') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainings as $model) :
                        $datecreate = '';
                        if ($model->getDate()) {
                            $date = new \Ilch\Date($model->getDate());
                        } else {
                            $date = new \Ilch\Date();
                        }
                        $datecreate = $date->format('d.m.Y', true);
                        ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_trainings', $model->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $model->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $model->getId()]) ?></td>
                            <td><?=date('d.m.Y - H:i', strtotime($model->getDate())) ?></td>
                            <td><?=date('d.m.Y - H:i', strtotime($model->getEnd())) ?></td>
                            <td><?=$this->escape($model->getTitle()) ?></td>
                            <td>
                                <?php
                                if ($model->getPeriodType()) {
                                    echo $periodTypes[$model->getPeriodType()];
                                    if ($model->getPeriodType() != 'days') {
                                        echo ' (x ' . $model->getPeriodDay() . ')';
                                    } else {
                                        echo ' (' . $periodDays[$model->getPeriodDay()] . ')';
                                    }
                                }
                                ?>
                            </td>
                            <td><?=($model->getPeriodType()) ? date('d.m.Y - H:i', strtotime($model->getRepeatUntil())) : '' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <?=$this->getTrans('noTraining') ?>
<?php endif; ?>
