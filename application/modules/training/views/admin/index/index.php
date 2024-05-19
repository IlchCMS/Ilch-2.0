<?php

/** @var \Ilch\View $this */

/** @var \Modules\Training\Models\Training[]|null $training */
$training = $this->get('training');
?>
<h1><?=$this->getTrans('manage') ?></h1>
<?php if ($training) : ?>
    <form method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-xl-2">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><?=$this->getCheckAllCheckbox('check_training') ?></th>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('dateTime') ?></th>
                        <th><?=$this->getTrans('title') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($training as $model) :
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
                            <td><?=date('d.m.Y - H:i', strtotime($model->getDate())) ?> </td>
                            <td><?=$this->escape($model->getTitle()) ?></td>
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
