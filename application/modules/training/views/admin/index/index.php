<h1><?=$this->getTrans('manage') ?></h1>
<?php if (!empty($this->get('training'))): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
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
                    <?php foreach ($this->get('training') as $training): ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_trainings', $training->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $training->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $training->getId()]) ?></td>
                            <td><?=date('d.m.Y - H:i', strtotime($training->getDate())) ?> </td>
                            <td><?=$training->getTitle() ?></a</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTraining') ?>
<?php endif; ?>
