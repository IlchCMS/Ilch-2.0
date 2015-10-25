<legend><?=$this->getTrans('manage') ?></legend>
<?php if ($this->get('training') != ''): ?>
    <form class="form-horizontal" method="POST" action="">
        <?=$this->getTokenField() ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="icon_width">
                    <col class="col-lg-2">
                    <col class="col-lg-10">
                    <col />
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
                            <td><input value="<?=$training->getId() ?>" type="checkbox" name="check_trainings[]" /></td>
                            <td><?=$this->getEditIcon(array('action' => 'treat', 'id' => $training->getId())) ?></td>
                            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $training->getId())) ?></td>
                            <td><?=date('d.m.Y - H:i', strtotime($training->getDate())) ?> </td>
                            <td><?=$training->getTitle() ?></a</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(array('delete' => 'delete')) ?>
    </form>
<?php else: ?>
    <?=$this->getTrans('noTraining') ?>
<?php endif; ?>
